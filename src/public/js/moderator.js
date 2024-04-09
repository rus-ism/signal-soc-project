jQuery(document).ready(function($){


    $(".studcnt").on('click', function(event){
      //event.stopPropagation();
      //event.stopImmediatePropagation();
      school_id = $(this).val();
      $('#stud_count_school_id').val(school_id);
      stud_count_school_id
      stud_count_clear();
      //alert("Ok, button clicked, school ID is "+school_id);
    });

/*Modal Save click */
    $("#stud_count_save").on('click', function(event){
      var school_id = $('#stud_count_school_id').val();
      var grade_5  = $('#5grade').val();
      var grade_6  = $('#6grade').val();
      var grade_7  = $('#7grade').val();
      var grade_8  = $('#8grade').val();
      var grade_9  = $('#9grade').val();
      var grade_10 = $('#10grade').val();
      var grade_11 = $('#11grade').val();

      //alert("Ok, button clicked, school ID is "+school_id);
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
      });
      var formData = {
        school_id:  school_id,
        grade5:     grade_5,
        grade6:     grade_6,
        grade7:     grade_7,
        grade8:     grade_8,
        grade9:     grade_9,
        grade10:    grade_10,
        grade11:    grade_11,
    };     

    $.ajax({
      type: "POST",
      url: "https://test.mcioko.edu.kz/moderator/tutors/change-count",
      data: formData,
      dataType: 'json',
      success: function (data) {
        console.log(data); 
        $('#exampleModal').modal('hide');
        countFill(data);       
      },
      error: function (data) {
        console.log('ERR');
          console.log(data);
      },
    });

    });
    

     function stud_count_clear() {
      $('.stud_count_input').val(null);
     };

     function countFill(data) {
      if (data.school_id == 0) {
        return 0;
      }
      console.log('Start filling...');
      console.log('#stud_count_btn'+data.school_id);
      str = 'Данные были обновлены, перезагрузите страницу чтобы отобразить';

      var count_btns = $(".studcnt");
      for(var i = 0; i < count_btns.length; i++){
        console.log($(count_btns[i]).val());
        if($(count_btns[i]).val() == data.school_id) {
          $(count_btns[i]).html('');
          $(count_btns[i]).append(str);
        }
      }        
     } //New question FILL



/*Delete tutor click */
$(".del_tutor").on('click', function(event){
  var user_id = $(this).prev().val();
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  });
  var formData = {
    id: user_id,
};     

$.ajax({
  method: "POST",
  url: "https://test.mcioko.edu.kz/moderator/tutors/delete/", 
  data: formData,
  dataType: 'json',
  success: function (data) {
    console.log(data); 
    location.reload();
      
  },
  error: function (data) {
    console.log('ERR');
      console.log(data);
  },
});

});     

/***************** Edit Tutor *******************
 * 
 * 
 * 
 * ********************************************/
/// Rename Shool ///////
$(".edit_tutor").on('click', function(event){
  //event.stopPropagation();
  //event.stopImmediatePropagation();
  $('#edit_tutor_modal').modal('show');
  user_id = $(this).prev().prev().val();
  $('#edit_tutor_modal_user_id').val(user_id);


  //alert("Ok, button clicked, school ID is "+school_id);
});

/*Modal Save click */
$("#edit_tutor_modal_save").on('click', function(event){
  var user_id = $('#edit_tutor_modal_user_id').val();
  var school_id = $('#selSchool').val();



  //alert("Ok, button clicked, school ID is "+school_id);
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  });
  var formData = {
    school_id:  school_id,
    user_id:    user_id,
};     

$.ajax({
  type: "POST",
  url: "/moderator/tutors/change_scool/",
  data: formData,
  dataType: 'json',
  success: function (data) {
    console.log(data); 
    location.reload();
    $('#exampleModal').modal('hide');       
  },
  error: function (data) {
    console.log('ERR');
      console.log(data);
  },
});

});

/***************** END Edit Tutor *******************
 * 
 * 
 * 
 * ********************************************/

/// Rename Shool ///////
$(".rename_school").on('click', function(event){
  //event.stopPropagation();
  //event.stopImmediatePropagation();
  $('#exampleModal').modal('show');
  school_id = $(this).attr('id');
  $('#rename_school_school_id').val(school_id);


  //alert("Ok, button clicked, school ID is "+school_id);
});

/*Modal Save click */
$("#rename_school_save").on('click', function(event){
  var school_id = $('#rename_school_school_id').val();
  var name_kz  = $('#name_kz').val();
  var name_ru  = $('#name_ru').val();


  //alert("Ok, button clicked, school ID is "+school_id);
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  });
  var formData = {
    school_id:  school_id,
    name_kz:    name_kz,
    name_ru:    name_ru,
};     

$.ajax({
  type: "POST",
  url: "/moderator/school/rename/",
  data: formData,
  dataType: 'json',
  success: function (data) {
    console.log(data); 
    $('#exampleModal').modal('hide');       
  },
  error: function (data) {
    console.log('ERR');
      console.log(data);
  },
});

});

});//Dock ready