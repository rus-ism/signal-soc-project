jQuery(document).ready(function($){

    $('.moderator_accept').click(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        var request_id = $(this).val();
        var formData = {
            request_id: request_id,
        };      
        $.ajax({
            type: "POST",
            url: "/admin/moderators/accept",
            data: formData,
            dataType: 'json',
            success: function (data) {
              //console.log(data); 
              NewFill(data);       
            },
            error: function (data) {
              console.log('ERR');
                console.log(data);
            },
        });
        
      });


     function NewFill(data) {
      //console.log(data);
      jQuery('#example1  tbody').html('');
      location.reload();
      /*
        data.user_requests.forEach( function(item){

          console.log(item);
          var str = `
          <td>${item.region}</td>
          <td>${item.fio}</td>
          <td>${item.date}</td>

          <td>
              <div class="custom-control custom-checkbox ">
                <input class="custom-control-input" type="checkbox" id="rq${item.id}" value="">
                <label for="rq${item.id}" class="custom-control-label"></label>                                                  
              </div>
          </td>
          `;

          jQuery('#example1  tbody').append(str); 
        
        }); // end foreach
      */
   
     } //New  FILL

});//Dock ready