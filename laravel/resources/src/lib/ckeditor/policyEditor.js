/**
 * CKEditor customizations for the non-admin view, for handling "track changes".
 *
 * This file is only used on the "business" side of the portal. It is not used by the admin views.
 */
 $(document).ready(function () {
  var user_info = window.ck_info.user || {};
  var is_required_policy = window.ck_info.policy && window.ck_info.policy.type === 'required' ? true : false;
  var editor;
  // counters to track number of modifications on current policy.
  var rejected = 0;
  var approved = 0;
  var total = 0;
  var pending_updates = 0;

  if ($("textarea[name=content_raw]").length) {
      editor = CKEDITOR.appendTo('editor-container', {
          readOnly: user_info.type == 'master' ? false : is_required_policy,
          extraPlugins: 'lite, sourcedialog',
          removePlugins: 'sourcearea,pastefromword,elementspath',
          height: 400
      }, $('#content_raw').val());
  }

  if (!editor) {
      return;
  }
  /**
   * Custom configs on LITE Plugin init event.
   */
  editor.on(LITE.Events.INIT, function (e) {
      var lite = e.data.lite;

      total = lite.countChanges();

      // disable tracking by default for consultants/admins
      if (total == 0) {
          lite.toggleTracking(user_info.type == 'master' ? false : true);
      }

      if (user_info.type == 'editor') {

          // Removing accept and reject actions from context menu when user is editor. Only Admins and Consultants
          // (pre-switch or post-switch with the ongoing_consultant_cc flag) can approve reject changes. I
          // couldn't find a way to remove the menu items directly, had to observe the
          // opening the context menu event and force the removal of the buttons.
          editor.contextMenu.addListener((function () {
              var commands = [];

              for (var i = 0; i < editor.contextMenu.items.length; i++) {
                  // skipping accept and reject commands
                  if (editor.contextMenu.items[i].command == LITE.Commands.ACCEPT_ONE ||
                      editor.contextMenu.items[i].command == LITE.Commands.REJECT_ONE
                  ) {
                      continue;
                  }
                  commands.push(editor.contextMenu.items[i]);
              }

              // re-assigning the commands.
              editor.contextMenu.items = commands;
          }).bind(this));
      }

      lite.setUserInfo(user_info);

      window.lite = lite;

      updateCounters();
      updateTracking();
  });


  /**
   * Intercepting REJECT & ACCEPT commands (one or all), to update counters and other custom behaviors
   */
  editor.on('beforeCommandExec', function (e) {
      // rejecting one
      if (e.data.name === 'lite-rejectone') {
          // update rejected counter.
          rejected++;
      }

      // rejecting all
      if (e.data.name === 'lite-rejectall') {
          // update rejected counter.
          rejected += lite.countChanges();
      }

      // accepting one
      if (e.data.name === 'lite-acceptone') {
          approved++;
      }

      // accepting all
      if (e.data.name === 'lite-acceptall') {
          approved += lite.countChanges();
      }

      updateCounters();

      return true;
  });


  editor.on('afterCommandExec', function (e) {
      if (e.data.name === 'lite-toggletracking') {
          updateTracking();
      }
  });

  /**
   * Updates counters
   */
  function updateCounters() {

      pending_updates = total - approved - rejected;

      $('#mod_counter').val(total);

      $('#mod_counter_approved').val(approved);
      $('#mod_counter_rejected').val(rejected);
      $('#mod_counter_pending').val(pending_updates);

      updateApprovalButtons();
  }

  function updateApprovalButtons() {
      $('#accept').attr('disabled', true);
      $('#reject').attr('disabled', true);

      if (pending_updates > 0) {
          return;
      }

      if (rejected > 0) {
          $('#reject').attr('disabled', false);
      } else {
          $('#accept').attr('disabled', false);
      }
  }

  function updateTracking() {
      $('#is_tracking').val(lite._isTracking)
  }

});