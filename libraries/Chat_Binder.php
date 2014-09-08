<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a wrapper of the XmppPrebind third-party lib.
 *
 * @license MIT (https://github.com/rmalibiran/pyrocms-cchat/blob/master/LICENSE)
 * @author    Richard Malibiran
 * @website   http://richard.malibiran.com
 */
class Chat_Binder
{

  private $binder = 'bosh';

  public function __construct()
  {
    
  }

  public function getXmppBinder($host, $boshUri, $user, $password, $resource = null) 
  {
    $xmppBinder = null;
    if ($this->binder == 'bosh') {
      $xmppBinder = $this->_boshBinder($host, $boshUri, $user, $password, $resource);
    } else {
      $xmppBinder = $this->_genericXmppBinder($host, $boshUri, $user, $password, $resource);
    }

    return $xmppBinder;
  }

  private function _genericXmppBinder($host, $boshUri, $user, $password, $resource = null)
  {
    require_once "addons/".SITE_REF."/modules/cchat/vendors/candy-chat/xmpp-prebind/XmppPrebind.php"; 
    $xmppBinder = new XmppPrebind(
      $host,
      $boshUri,
      $resource
    );

    return $xmppBinder;
  }

  private function _boshBinder($host, $boshUri, $user, $password, $resource = null)
  {
    require_once "addons/".SITE_REF."/modules/cchat/vendors/candy-chat/xmpp-prebind/XmppBosh.php"; 
    $xmppBinder = new XmppBosh(
      $host,
      $boshUri,
      $resource
    );

    return $xmppBinder;
  }

}