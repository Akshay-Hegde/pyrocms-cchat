<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Cchat Admin Controller
 *
 * @license MIT (https://github.com/rmalibiran/pyrocms-cchat/blob/master/LICENSE)
 * @author    Richard Malibiran
 * @website   http://richard.malibiran.com
 */
class Admin extends Admin_Controller
{
  protected $section = 'cchat';

  public function __construct()
  {
    parent::__construct();

    $this->lang->load('cchat');
    $this->load->model('cchat_m');
    $this->load->model('extensions/cext_user_m');

    // user needs
    $this->lang->load('users/user');
    $this->load->model('users/user_m');
    $this->load->model('groups/group_m');
    $this->load->helper('users/user');

    // $this->template->append_js('module::admin.js')
    //       ->append_css('module::admin.css'); 

    // config
    $this->load->config('cchat');
    $this->_chatHost = $this->config->item('cchat:host');
    $this->_prebindUrl = $this->config->item('cchat:prebindURL');
    $this->_isGeneratePass = $this->config->item('cchat:isGeneratePass');
    $this->_defaultPass = $this->config->item('cchat:defaultPass');
    $this->_srvUrl = $this->config->item('cchat:srvUrl');

    // groups fix
    if ($this->current_user->group != 'admin') 
    {
      $this->template->groups = $this->group_m
        ->where_not_in('name', 'admin')
        ->get_all();
    } 
    else 
    {
      $this->template->groups = $this->group_m->get_all();
    }
    
    $this->template->groups_select = array_for_select(
      $this->template->groups, 
      'id', 
      'description'
    );
  }

  public function index()
  {
    $base_where = array('active' => 0);

    // ---------------------------
    // User Filters
    // ---------------------------
    // Determine active param
    $base_where['active'] = $this->input->post('f_module') ? 
      (int)$this->input->post('f_active') : 
      $base_where['active'];

    // Determine group param
    $base_where = $this->input->post('f_group') ? 
      $base_where + array('group_id' => (int)$this->input->post('f_group')) : 
      $base_where;

    // Keyphrase param
    $base_where = $this->input->post('f_keywords') ? 
      $base_where + array('name' => $this->input->post('f_keywords')) : 
      $base_where;

    // Create pagination links
    $pagination = create_pagination(
      'admin/cchat/index', 
      $this->user_m->count_by($base_where)
    );

    //Skip admin
    $skip_admin = ( $this->current_user->group != 'admin' ) ? 
      'admin' : 
      '';

    $users = $this->cchat_m->getUsersWithBinding(
      $base_where, 
      $skip_admin, 
      $pagination
    );

    // Unset the layout if we have an ajax request
    if ($this->input->is_ajax_request()) {
      $this->template->set_layout(false);
    }

    // Render the view
    $this->template
      ->title($this->module_details['name'])
      ->set('pagination', $pagination)
      ->set('users', $users)
      ->set_partial('filters', 'admin/partials/filters')
      ->append_js('admin/filter.js');

    $this->input->is_ajax_request() ? 
      $this->template->build('admin/tables/users') : 
      $this->template->build('admin/index');
  }

  public function statupdate($userId)
  {
    $result = false;
    if ($userId) {

      $userChatAccount = $this->cchat_m->get_by(array(
        'user_id' => $userId
      ));

      $user = $this->cext_user_m->get(array('id' => $userId));
      if ($user) {

        if ($userChatAccount) {
          $result = $this->_reverseStatus($userChatAccount);
        } else {
          // i'll create a chat account for you kid
          $result = $this->_createAccount($user);
        }

      } // user checker

    }

    // final assessment to return the result
    if ($result) {
      $this->session->set_flashdata('success', 'User\'s chat account has been activated.');
    } else {
      $this->session->set_flashdata('Error', 'Invalid chat administrator command.');
    }

    redirect('admin/cchat');
  }

  private function _createAccount($user) 
  {
    $username = preg_replace("/[^a-zA-Z0-9]+/", "", $user->username);
    $username = strtolower($username);

    $insertSubject = array(
      'handle' => $username,
      'handle_display' => $user->display_name,
      'password' => $this->_generatePassword(),
      'status' => 'active', 
      'user_id' => $user->id
    );

    $insertResult = $this->cchat_m->insert($insertSubject);
    if($insertResult) {
      // do the magic here! :)
      $this->_generateLiveChatAccount(
        $insertSubject['handle'],
        $insertSubject['password'],
        $insertSubject['handle_display']
      );
    }

    return $insertResult;
    
  }

  private function _reverseStatus($userChatAccount)
  {

    if ($userChatAccount->status == 'active') {
      $userChatAccount->status = 'inactive';
    } else {
      $userChatAccount->status = 'active';
    }

    return $this->_updateChatAccount($userChatAccount);
  }

  private function _updateChatAccount($userChatAccount) 
  {
    return $this->cchat_m->update(
      $userChatAccount->id, 
      $userChatAccount
    );
  }

  /**
  * @TODO: do the password binding depending on the config
  */
  private function _generatePassword()
  {
    return $this->_defaultPass;
  }

  /**
  * @TODO: move me to a service model/utility
  */
  private function _generateLiveChatAccount($user, $pass, $disp)
  {
    $ch = curl_init();

    $registerUrl = $this->_srvUrl . 'register';
    curl_setopt($ch, CURLOPT_URL, $registerUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt(
      $ch, 
      CURLOPT_POSTFIELDS,
      http_build_query( array(
        'user' => $user,
        'pass' => $pass,
        'disp' => $disp
        )
      )
    );

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close($ch);
    // var_dump($server_output); exit;

    // further processing, but be forgiving for now.. :) 
    // if ($server_output == "OK") { ... } else { ... }
  }

}