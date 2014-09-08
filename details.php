<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Cchat Module
 * 
 * NOTE: If you are using prosody, make sure that you are
 *     using hashed authentication method!
 * refer to: http://prosody.im/doc/modules/mod_auth_internal_hashed
 *
 * @license MIT (https://github.com/rmalibiran/pyrocms-cchat/blob/master/LICENSE)
 * @author    Richard Malibiran
 * @website   http://richard.malibiran.com
 */
class Module_Cchat extends Module {

  public $version = '1.0';

  public function info()
  {
    return array(
      'name' => array(
        'en' => 'Cchat'
      ),
      'description' => array(
        'en' => 'This is a custom Chat module.'
      ),
      'frontend' => TRUE,
      'backend' => TRUE,
      'menu' => 'content',
      'sections' => array(
        'cchat' => array(
          'name'  => 'cchat:title',
          'uri'   => 'admin/cchat',
            'shortcuts' => array(
              'create' => array(
                'name'  => 'cchat:list',
                'uri'   => 'admin/cchat/',
                'class' => 'list'
                )
            )
          )
        )
    );
  }

  public function install()
  {
    $this->dbforge->drop_table('cchat_account');

    // Create the class record and recordbook table.
    $tables = array(
      'cchat_account' => array(
        'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
        'handle' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => false),
        'handle_display' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => true),
        'password' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => false),
        'status' => array('type' => 'ENUM', 'constraint' => array('active', 'inactive'), 'default' => 'active'),
        'user_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false)
      )
    );

    if ( ! $this->install_tables($tables)) {
      return false;
    }

    return true;
  }

  public function uninstall()
  {
    $this->dbforge->drop_table('cchat_account');

    $this->db->delete('settings', array('module' => 'cchat'));
    {
      return TRUE;
    }
  }


  public function upgrade($old_version)
  {
    // Your Upgrade Logic
    return TRUE;
  }

  public function help()
  {
    // Return a string containing help info
    // You could include a file and return it here.
    return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
  }
}
/* End of file details.php */
