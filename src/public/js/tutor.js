jQuery(document).ready(function($){


/*  Add Student */
jQuery('#student_add_submint').on('click', function() {
  school_id = $("#school_id").val();
  grade = $("#igrade").val();
  var data = {
    school_id: school_id,
    grade: grade
  };
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    type: "POST",
    url: "/tutor/schoolar/check_count",
    data: data,
    dataType: 'json',
    success: function (data) {
      console.log(data); 
      if (data.error == 0) {
          add_schoolar(data);
      } else if(data.error == 1) {
          alert(`Превышен контингент, указанный модератором (${data.count}). Добавлено - ${data.current_count}`);
      } else if(data.error == 2) {
        alert(`Не заполнен контингент модератором`);
    }
             
      //fill(quiz_id);
      
    },
    error: function (data) {
      console.log('ERR');
        console.log(data);
    },
});   

});

function add_schoolar(data) {
  $("#add_schoolar_form").submit();
}


jQuery('#example1').on('click', '.delete_student', function() {
    var user_id = $(this).prev().val();
    var action = 'delete';

    var data = {
        action: action,
        user_id: user_id,
    }
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      url: "/tutor/schoolar/delete",
      data: data,
      dataType: 'json',
      success: function (data) {
        console.log(data); 
        if (data.error == 0) {
            window.location.replace("https://test.mcioko.edu.kz/tutor/schoolar");
        } else {
            alert("У данного ученика есть результаты тестирования, удаление невозможно. Обратитесь к администратору");
        }
               
        //fill(quiz_id);
        
      },
      error: function (data) {
        console.log('ERR');
          console.log(data);
      },
  }); 



});

/*------ Unlink student -------------*/

jQuery('#example1').on('click', '.unlink_student', function() {
    var user_id = $(this).prev().val();
    var action = 'unlink';

    var data = {
        action: action,
        user_id: user_id,
    }
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      url: "/tutor/schoolar/unlink",
      data: data,
      dataType: 'json',
      success: function (data) {
        console.log(data); 
        if (data.error == 0) {
            window.location.replace("https://test.mcioko.edu.kz/tutor/schoolar");
        } else {
            alert("У данного ученика есть результаты тестирования, удаление невозможно. Обратитесь к администратору");
        }
               
        //fill(quiz_id);
        
      },
      error: function (data) {
        console.log('ERR');
          console.log(data);
      },
  }); 



});

/*------ END Unlink student -------------*/

/*-------- Edit Answer ------------------*/
jQuery('#example1').on('click', '.edit_student', function() {
    $('#editStudent').modal('show');
    var action = 'edit';
    var user_id = $(this).prev().val();
    
    var fio = $(this).prev().prev().val();
    var grade = $(this).prev().prev().prev().val();
    var litera = $('#litera_'+user_id).val();

    $('#editStudent_action').val(action);
    $('#editStudent_user_id').val(user_id);
    $('#editStudent_fio').val(fio);

    $('#editStudent_grade').val(grade);
    $('#editStudent_litera').val(litera);

    jQuery('#editStudent_save').removeClass( "modal_add" );
    jQuery('#editStudent_save').addClass( "modal_edit" );
    
   // $('#answerModal').modal({ show: true})
  });



// Modal safe button click
jQuery('#editStudent').on('click', '.modal_edit', function() {
  $('#editStudent').modal('hide');
  var user_id = $("#editStudent_user_id").val();
  var action = $('#editStudent_action').val();
  var fio = $('#editStudent_fio').val();
  var grade = $('#editStudent_grade').val();
  var litera = $('#editStudent_litera').val();
  var data = {
      action: action,
      user_id: user_id,
      fio: fio,
      grade: grade,
      litera: litera,
  }
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    type: "POST",
    url: "/tutor/schoolar/edit",
    data: data,
    dataType: 'json',
    success: function (data) {
      console.log(data);        
      //fill(quiz_id);
      window.location.replace("https://test.mcioko.edu.kz/tutor/schoolar");
    },
    error: function (data) {
      console.log('ERR');
        console.log(data);
    },
}); 

});



  /*-------- END Edit Answer ------------------*/    
   
});//Dock ready