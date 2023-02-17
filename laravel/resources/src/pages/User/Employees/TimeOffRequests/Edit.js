$(document).ready(function () {
  $(".date-picker").datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    yearRange: "-100:+10",
    onSelect: function (selectedDate) {
      let id = $(this).attr("id");
      if (id == "start_at") {
        $("#end_at").datepicker('option', 'minDate', selectedDate);
      }
    }
  });
});