(function($){
  $('#employees-number').on('submit', function(e){
      e.preventDefault();
      
      var json = {};
      var employees = $('input[name="employees"]').val();

      // validating quantity entered.
      if ( isNaN(employees) || employees < 1 ) {
          $('#feedback').empty().html('<span style="color: red;">Please enter a valid employee number.</span>');
          return;
      }

      json.employees = employees;
      json._token = $('input[name="_token"]').val();

      $.post($(this).prop('action'), json, function(data){
          if(data.success === true) {
              $('#feedback').empty().html('<span style="color: blue;">Thanks! Your employee number has been updated.</span>');
              $('.errors').remove();
          }
      });
  });
})(jQuery);