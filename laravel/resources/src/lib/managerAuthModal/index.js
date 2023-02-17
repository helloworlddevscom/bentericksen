$(document).ready(function () {

  if( $("#role_id").length) {
    $("#role_id").on("change", function () {
      // Manager value select code is 3
      if (this.value === '3') {
        $("#managerAuthConfirmModal").modal("show");
      }
    });
  }

  if( $("#user_role").length) {
    $("#user_role").on("change", function () {
      // Manager value select code is 3
      if (this.value === '3') {
        $("#managerAuthConfirmModal").modal("show");
      }
    });
  }

  $("#js-reset-auth-permission").on("click", function () {
    // Employee value select code is 5

    // For authorization tab
    if( $("#role_id").length) {
      $("#role_id").val("5");
    }

    // For new employee form
    if( $("#user_role").length) {
      $("#user_role").val("5");
    }
  });

});