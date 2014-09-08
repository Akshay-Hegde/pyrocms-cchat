pyrocms-cchat
=============

A simple chat module for PyroCMS (2.2.x). This uses conversejs and requires an XMPP server (using Prosody at the moment).

Features
=================
- Create/Register a chat account (Administrators only)
- Chat to an XMPP server (using Prosody at the moment)
- HTTP Binding (user is logged-in to chat as long as he is logged-in to the webiste)

Installation
=================
1. Clone or download the source code
2. Extract it to the addons directory (you can also upload it using the upload module tool from the administrator's panel). Rename the pyrocms-cchat directory to cchat before installing. :)
3. Install it (just click the install button from the module pages of the administrator's panel)

TODO
=================
- Add settings for javascripts (or even css?) to fix the possible conflict with templates that uses the libraries that we are using. Examples are:
    - template_has_conversejs
    - template_has_jquery
- Create permissions on who can create a chat account for a user
- Automatically create a chat user upon registration
- Port to more chat providers (works best with a prosodyctl masking I made on node.js. will create a repo for it soon)
- Handle the registration failure properly
- Add authentication token for the registration routine
- Add tests

NOTE
=================
- I manually built conversejs to added the jquery no-conflict codes. If you are going to use the jquery included inside coversejs, use the original_converse.min.js file instead. (You can change it on the events.php file).
- I used spaces as tabs, tab-width 2

Thanks!
=================
- [PyroCMS](https://www.pyrocms.com)
- [ConverseJS](https://conversejs.org)
- [Candy Chat (for the XMPP prebinding codes/ideas)](https://github.com/candy-chat/xmpp-prebind-php)