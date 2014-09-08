<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Public Cchat module controller
 *
 * @license MIT (https://github.com/rmalibiran/pyrocms-cchat/blob/master/LICENSE)
 * @author    Richard Malibiran
 * @website   http://richard.malibiran.com
 */
class Cchat extends Public_Controller
{

  // settings from config
  private $_chatHost;
  private $_prebindUrl;
  private $_isGeneratePass;
  private $_defaultPass;
  private $_srvUrl;

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cchat_m');
    $this->load->library('Chat_Binder');

    $this->load->config('cchat');
    $this->_chatHost = $this->config->item('cchat:host');
    $this->_prebindUrl = $this->config->item('cchat:prebindURL');
    $this->_isGeneratePass = $this->config->item('cchat:isGeneratePass');
    $this->_defaultPass = $this->config->item('cchat:defaultPass');
    $this->_srvUrl = $this->config->item('cchat:srvUrl');
  }

  public function index()
  {
    echo "Cchat index page";
  }

  /**
  * TODO: if the chat session is already to ci()->session, load it
  *       otherwise, create a new chat session
  */
  public function prebind()
  {
    
    $data = array(
      'status' => 0,
      'sessInfo' => array()
    );

    // if logged-in, do the reggae,
    // otherwise do the justin bieber
    if (is_logged_in()) {

      $myChatAccount = $this->cchat_m->get_by(array(
        'user_id' => $this->current_user->id,
        'status' => Cchat_m::STATUS_ACTIVE
      ));

      if ($myChatAccount) {

        $user = $myChatAccount->handle;
        $password = $myChatAccount->password;

        $conn = $this->chat_binder->getXmppBinder(
          $this->_chatHost,
          $this->_prebindUrl,
          $user,
          $password
        );

        try {
          $conn->connect($user, $password);
          // ---
          // use this if alternate prebinding is needed
          // ---
          // $conn->auth(); 

          $sessionInfo = $conn->getSessionInfo();
          $sessionInfo['bosh_service_url'] = $this->_prebindUrl;

          $data['status'] = 1;
          $data['sessInfo'] = $sessionInfo;

        } catch (Exception $e) {
          // var_dump($e); exit;
          // WARNING: smelly code!
          // error suppression
        }
        
      }

    } // login checker

    $this->_sendJsonResponse($data);

  }

  /**
  * TODO: make strict json response headers
  */
  private function _sendJsonResponse($data)
  {
    echo json_encode($data); exit;
  }

}