<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @license MIT (https://github.com/rmalibiran/pyrocms-cchat/blob/master/LICENSE)
 * @author    Richard Malibiran
 * @website   http://richard.malibiran.com
 */
class Events_Cchat {
    
  protected $ci;
    
  public function __construct()
  {
    $this->ci =& get_instance();

    // create a namespace for the cchat assets
    Asset::add_path ('cchat', ADDONPATH . 'modules/cchat/');
    
    //register the public_controller event
    Events::register('public_controller', array($this, 'public_run'));
    Events::register('admin_controller', array($this, 'admin_run'));
  }
    
  public function public_run($params)
  {
    $this->_appendAssets();
  }

  public function admin_run($params)
  {
    $this->_appendAssets(true);
  }

  private function _appendAssets($isAdmin = false)
  {
    if (isset($_GET['mode']) && $_GET['mode'] == 'simple') {
      // do nothing            
    } else {

      $this->ci->template->append_css("cchat::converse.reset.css");
      $this->ci->template->append_css("cchat::converse.min.css");

      if ($isAdmin) {
          $this->ci->template->append_css("cchat::converse.admin.overrides.css");
      } else {
          $this->ci->template->append_css("cchat::converse.overrides.css");
      }

      $this->ci->template->append_js("cchat::noconflict_converse.min.js");
      $this->ci->template->append_js("cchat::converse.handle.js");
        
    }
  }

}
/* End of file events.php */