<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * An extension of the Users/User_m.
 *
 * @author ORIGINAL: PyroCMS Dev Team, EXTENDED BY: Richard Malibiran - CodeCarabao
 * @package PyroCMS\Core\Modules\Users\Models
 *
 * @modification: removed the functionality that gets the current user is no user is found.
 */
require_once 'system' . DIRECTORY_SEPARATOR . 
  'cms' . DIRECTORY_SEPARATOR . 
  'modules' . DIRECTORY_SEPARATOR . 
  'users' . DIRECTORY_SEPARATOR . 
  'models' . DIRECTORY_SEPARATOR . 
  'user_m.php';

class Cext_user_m extends User_m
{

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Get a specified (single) user.
   *
   * @overrides parent::get()
   * @param array $params
   *
   * @return object
   */
  public function get($params)
  {
    if (isset($params['id']))
    {
      $this->db->where('users.id', $params['id']);
    }

    if (isset($params['username']))
    {
      $this->db->where('LOWER('.$this->db->dbprefix('users.username').')', strtolower($params['username']));
    }

    if (isset($params['email']))
    {
      $this->db->where('LOWER('.$this->db->dbprefix('users.email').')', strtolower($params['email']));
    }

    if (isset($params['role']))
    {
      $this->db->where('users.group_id', $params['role']);
    }

    $this->db
      ->select($this->profile_table.'.*, users.*')
      ->limit(1)
      ->join('profiles', 'profiles.user_id = users.id', 'left');
    
    $data = $this->db->get('users')->row(); 
    // double check because it defaults to the first item
    // the user being searched isn't found.
    if (
        ( isset($params['id'])  && $data->id == $params['id'] ) || 
        ( isset($params['username']) && strtolower($data->username) == strtolower($params['username']) ) || 
        ( isset($params['email']) && strtolower($data->email) == strtolower($params['email']) ) 
      ) {
      // do nothing

    } else {
      $data = null;
    }

    return $data;
  }

}