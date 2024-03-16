jQuery(document).ready(function($){


    $('#selRegion').on('change', function() {
      if (this.value == 0) {
          $('#selSchool').addAttr('disabled');
          jQuery('#selSchool').empty();        
      } else {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              }
          });        
            var region_id = this.value
            var formData = {
              region_id: region_id,
            };
            console.log(formData);
          $.ajax({
              type: "POST",
              url: "/school/byregion",
              data: formData,
              dataType: 'json',
              success: function (data) {
                console.log(data);
      
                schoolFill(data);
      
              },
              error: function (data) {
                console.log('ERR');
                  console.log(data);
              }
          });        
      };
      });
    

     function schoolFill(data) {
      console.log('Start filling...');
      $('#selSchool').removeAttr('disabled');
      jQuery('#selSchool').empty();
      data.forEach( function(item){
        var str = `<option value="${item.id}">${item.name}</option>`;
        jQuery('#selSchool').append(str);
      }); // end foreach
        
     } //New question FILL

});//Dock ready