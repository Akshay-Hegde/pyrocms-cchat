<?php 
/* The hostname of your website or chat host */
$config['cchat:host'] = '<sample: mywebhost.com>';

/* Http Pre-Bind Url (BOSH)
  If using prosody, refer to: http://prosody.im/doc/setting_up_bosh
*/
$config['cchat:prebindURL'] = '<sampple: http://mywebhost.com/http-bind>';

/* 
  The host where to register the new users. 
  NOTE: I will be creating a new repo soon for the simple prosodyctl bash masking I made using Node.js.
*/
$config['cchat:srvUrl'] = '<sampple: http://mywebhost.com:4370>';

/* 
  Is the user user generated a new password? 
  @TODO: create a manual window where the administrators get to type new passwords for users
*/
$config['cchat:isGeneratePass'] = true;

/* Default password */
$config['cchat:defaultPass'] = 'gitm1mt';
// $config['cchat:defaultPass'] = '8O0y31v12KYGlD96j0lM';

