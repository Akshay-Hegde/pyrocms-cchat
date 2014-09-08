<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Cchat Model
 *
 * @license MIT (https://github.com/rmalibiran/pyrocms-cchat/blob/master/LICENSE)
 * @author    Richard Malibiran
 * @website   http://richard.malibiran.com
 */
class Cchat_m extends MY_Model 
{

  const STATUS_ACTIVE = 'active';
  const STATUS_INACTIVE = 'inactive';

  public function __construct()
  {
    parent::__construct();

    $this->_table = 'cchat_account';
  }

  public function getUsersWithBinding($base_where, $skip_admin, $pagination)
  {
    // Using this data, get the relevant results
    $this->db->order_by('active', 'desc')
      ->join('groups', 'groups.id = users.group_id')
      ->join('cchat_account', 'cchat_account.user_id = users.id', 'left outer')
      ->where_not_in('groups.name', $skip_admin)
      ->limit($pagination['limit'], $pagination['offset']);

    if ( ! empty($base_where['active'])) {
      $base_where['active'] = $base_where['active'] === 2 ? 0 : $base_where['active'];
      $this->db->where('active', $base_where['active']);
    }

    if ( ! empty($base_where['group_id'])) {
      $this->db->where('group_id', $base_where['group_id']);
    }

    if ( ! empty($base_where['name'])) {
      $this->db
        ->or_like('users.username', trim($base_where['name']))
        ->or_like('users.email', trim($base_where['name']));
    }

    $this->db
      ->select('profiles.*, g.description as group_name, users.*, cchat_account.id AS handle_id, cchat_account.handle, cchat_account.handle_display, cchat_account.password AS handle_password, cchat_account.status AS handle_status')
      ->join('groups g', 'g.id = users.group_id')
      ->join('profiles', 'profiles.user_id = users.id', 'left')
      ->group_by('users.id');

    return $this->db->get('users')->result();
  }

}