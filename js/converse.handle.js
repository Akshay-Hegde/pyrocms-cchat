$(document).ready(function() {

  var prebindCchat = function() {
    $.getJSON('/cchat/prebind', function (data) {
      
      if (data.status > 0) {
        var sess = data.sessInfo;
        converse.initialize({
          prebind: true,
          bosh_service_url: sess.bosh_service_url,
          jid: sess.jid,
          sid: sess.sid,
          rid: sess.rid,
          allow_otr: true,
          allow_contact_requests: false,
          auto_reconnect: true,
          auto_list_rooms: false,
          auto_subscribe: false,
          // debug: true,
          hide_muc_server: false,
          i18n: locales['en'],
          show_controlbox_by_default: true,
          xhr_user_search: false,
          roster_groups: true
        });
      }

    });
  };

  window.setTimeout(prebindCchat, 1000);

});