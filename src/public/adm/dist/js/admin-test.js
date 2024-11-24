jQuery(document).ready(function($){
  $('#answerModal').modal({ show: false})

   /*-------- DELETE Question ------------------*/

   jQuery('#question_table').on('click', '.delete_question', function() {
    var action = 'delete';
    var question_id = $(this).prev().val();
    var quiz_id =$("#quiz_id").val();
    $('#deleteQuestionModal_action').val('delete');
    $('#deleteQuestionModal_question_id').val(question_id);
    $('#deleteQuestionModal_quiz_id').val(quiz_id);
    $('#deleteQuestionModal').modal('show');

    console.log('quiz_id: '+$('#deleteQuestionModal_quiz_id').val());
  });


  // delete question modal button click
  jQuery('#deleteQuestionModal_save').click(function () {
    $('#deleteQuestionModal').modal('hide');
    var quiz_id =$("#quiz_id").val();
    var action = $('#deleteQuestionModal_action').val();
    var question_id = $('#deleteQuestionModal_question_id').val();
    var data = {
        action: action,
        question_id: question_id,
    }
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      url: "/admin/test/question/delete",
      data: data,
      dataType: 'json',
      success: function (data) {
        console.log(data);        
        fill(quiz_id);
      },
      error: function (data) {
        console.log('ERR');
          console.log(data);
      },
  }); 

  });

  /*-------- END DELETE Question ------------------*/


    /*-------- Edit Question ------------------*/
    jQuery('#question_table').on('click', '.edit_question', function() {
      $('#questionModal').modal('show');
      var action = 'edit';
      var question_id = $(this).prev().val();
      var quiz_id =$("#quiz_id").val();

      $('#questionModal_action').val(action);
      $('#questionModal_quiz_id').val(quiz_id);
      $('#questionModal_question_id').val(question_id);

      jQuery('#questionModal_save').removeClass( "modal_add" );
      jQuery('#questionModal_save').addClass( "modal_edit" );

    });

    // Modal safe button click
    jQuery('#questionModal').on('click', '.modal_edit', function() {
      $('#questionModal').modal('hide');
      var action = $('#questionModal_action').val();
      var question_id = $('#questionModal_question_id').val();
      var quiz_id = $('#questionModal_quiz_id').val();
      var question_text = $('#questionModal_question_text').val();
      var question_text_kz = $('#questionModal_question_text_kz').val();
      var question_description = $('#questionModal_question_description').val();
      var question_description_kz = $('#questionModal_question_description_kz').val();
      var data = {
          action: action,
          quiz_id: quiz_id,
          question_id: question_id,
          question_text: question_text,
          question_text_kz: question_text_kz,
          question_description: question_description,
          question_description_kz:question_description_kz
      }
      console.log(data);
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "POST",
        url: "/admin/test/question/update",
        data: data,
        dataType: 'json',
        success: function (data) {
          console.log(data);        
          fill(quiz_id);
        },
        error: function (data) {
          console.log('ERR');
            console.log(data);
        },
    }); 

    });    


    /*-------- END Edit Question ------------------*/


  /*-------- Add question ------------------*/
  jQuery('#question_table').on('click', '.add_question', function() {
    $('#questionModal').modal('show');
    var action = 'add';
    var quiz_id = $("#quiz_id").val();
    $('#questionModal_action').val(action);
    $('#questionModal_quiz_id').val(quiz_id);
    
    jQuery('#questionModal_save').addClass( "modal_add" );
    jQuery('#questionModal_save').removeClass( "modal_edit" );
   // $('#answerModal').modal({ show: true})
  });

// Modal safe button click
  jQuery('#questionModal').on('click', '.modal_add', function() {
    $('#questionModal').modal('hide');
    var action = $('#questionModal_action').val();
    var quiz_id = $('#questionModal_quiz_id').val();
    if (action != 'add') { return; }
    var question_text = $('#questionModal_question_text').val();
    var question_text_kz = $('#questionModal_question_text_kz').val();
    var question_description = $('#questionModal_question_description').val();
    var question_description_kz = $('#questionModal_question_description_kz').val();
    var data = {
        action: action,
        quiz_id: quiz_id,
        question_text: question_text,
        question_text_kz: question_text_kz,
        question_description: question_description,
        question_description_kz: question_description_kz
    }
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      url: "/admin/test/question/add",
      data: data,
      dataType: 'json',
      success: function (data) {
        console.log(data);        
        fill(quiz_id);
      },
      error: function (data) {
        console.log('ERR');
          console.log(data);
      },
  }); 

  });



  /*-------- END Add question ------------------*/   
  
  

  /*-------- DELETE Answer ------------------*/
  jQuery('#question_table').on('click', '.delete_answer', function() {
    var action = 'delete';
    $('#deleteAnswerModal_action').val(action);
    
    var answer_id = $(this).prev().val();
    var question_id = $(this).prev().prev().val();
    var quiz_id =$("#quiz_id").val();
    $('#deleteAnswerModal_question_id').val(question_id);
    $('#deleteAnswerModal_question_id').val(question_id);
    $('#deleteAnswerModal_answer_id').val(answer_id);
    $('#deleteAnswerModal').modal('show');
  });


  // Modal safe button click
  jQuery('#deleteAnswerModal_save').click(function () {
    $('#deleteAnswerModal').modal('hide');
    var quiz_id =$("#quiz_id").val();
    var action = $('#deleteAnswerModal_action').val();
    var answer_id = $('#deleteAnswerModal_answer_id').val();
    var data = {
        action: action,
        answer_id: answer_id,
    }
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      method: "POST",
      url: "/admin/test/answer/delete",
      data: data,
      dataType: 'json',
      success: function (data) {
        console.log(data);        
        fill(quiz_id);
      },
      error: function (data) {
        console.log('ERR');
          console.log(data);
      },
  }); 

  });

  /*-------- END DELETE Question ------------------*/    



  /*-------- Edit Answer ------------------*/
  jQuery('#question_table').on('click', '.edit_answer', function() {
    $('#answerModal').modal('show');
    var action = 'edit';
    var answer_id = $(this).prev().val();
    var question_id = $(this).prev().prev().val();
    var question_text = $(this).prev().prev().prev().val();
    var answer_text = $('#answer_text_'+answer_id).val();
    var quiz_id = $("#quiz_id").val();

    $('#answerModal_action').val(action);
    $('#answerModal_answer_id').val(answer_id);
    $('#answerModal_question_id').val(question_id);

    $('#answerModal_qestion_text').html(question_text);
    $('#answerModal_answer_text').attr('placeholder',answer_text);

    jQuery('#answerModal_save').removeClass( "modal_add" );
    jQuery('#answerModal_save').addClass( "modal_edit" );
    
   // $('#answerModal').modal({ show: true})
  });



// Modal safe button click
jQuery('#answerModal').on('click', '.modal_edit', function() {
  $('#answerModal').modal('hide');
  var quiz_id = $("#quiz_id").val();
  var action = $('#answerModal_action').val();
  var question_id = $('#answerModal_question_id').val();
  var answer_id = $('#answerModal_answer_id').val();
  var answer_text = $('#answerModal_answer_text').val();
  var answer_text_kz = $('#answerModal_answer_text_kz').val();
  var answer_scope = $('#answerModal_answer_scope').val();
  var data = {
      action: action,
      question_id: question_id,
      answer_id: answer_id,
      answer_text: answer_text,
      answer_text_kz: answer_text_kz,
      answer_scope: answer_scope
  }
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    method: 'post',
    type: 'POST',
    url: "/admin/test/answer/update",
    data: data,
    dataType: 'json',
    success: function (data) {
      console.log(data);        
      fill(quiz_id);
    },
    error: function (data) {
      console.log('ERR');
        console.log(data);
    },
}); 

});



  /*-------- END Edit Answer ------------------*/    



    /*-------- Add Answer ------------------*/
    jQuery('#question_table').on('click', '.add-answer', function() {
      $('#answerModal').modal('show');
      var action = 'add';
      
      var question_text = $(this).prev().prev().val();
      var question_id = $(this).prev().val();      
      var quiz_id = $("#quiz_id").val();

      $('#answerModal_action').val(action);
      $('#answerModal_question_id').val(question_id);

      $('#answerModal_qestion_text').html(question_text);

      jQuery('#answerModal_save').addClass( "modal_add" );
      jQuery('#answerModal_save').removeClass( "modal_edit" );      
     // $('#answerModal').modal({ show: true})
    });


    // Modal safe button click
    jQuery('#answerModal').on('click', '.modal_add', function() {
      $('#answerModal').modal('hide');
      var quiz_id = $("#quiz_id").val();
      var action = $('#answerModal_action').val();
      var question_id = $('#answerModal_question_id').val();
      var answer_text = $('#answerModal_answer_text').val();
      var answer_text_kz = $('#answerModal_answer_text_kz').val();
      var answer_scope = $('#answerModal_answer_scope').val();
      var data = {
          action: action,
          question_id: question_id,
          answer_text: answer_text,
          answer_text_kz: answer_text_kz,
          answer_scope: answer_scope
      }
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "POST",
        url: "/admin/test/answer/add",
        data: data,
        dataType: 'json',
        success: function (data) {
          console.log(data);        
          fill(quiz_id);
        },
        error: function (data) {
          console.log('ERR');
            console.log(data);
        },
    }); 

    });    
    /*-------- END Add Answer ------------------*/  


    /*--------- Change Grade Acl ---------------*/
    jQuery('.check-grade-edit').click(function () {
      if ($(this).is(':checked')) {
        var action = 'set';
      } else {
        var action = 'unset';
      };
      var grade = $(this).val();
      var quiz_id = $("#quiz_id").val();

      var data = {
        action: action,
        grade: grade,
        quiz_id: quiz_id,
        }
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "POST",
          url: "/admin/test/quizacl/change",
          data: data,
          dataType: 'json',
          success: function (data) {
            console.log(data);        
            //fill(quiz_id);
          },
          error: function (data) {
            console.log('ERR');
              console.log(data);
          },
        });       
    });

    /*--------- END Change Grade Acl -----------*/

    jQuery('#fill_table').click(function () {
      fill(6);
    });

    jQuery('#un_fill_table').click(function () {
      $('.overlay').remove();
    });    




      /*-------- Add interpretation ------------------*/
      jQuery('#interprets_table').on('click', '.add_interpret', function() {
        $('#interpretModal').modal('show');
        var action = 'add';
        var quiz_id = $("#quiz_id").val();
        $('#interpretModal_action').val(action);
      });

    // Modal safe button click
      jQuery('#interpretModal').on('click', '.modal_add', function() {
        
        $('#interpretModal').modal('hide');
        var action = $('#interpretModal_action').val();
        var quiz_id = $("#quiz_id").val();
        if (action != 'add') { return; }
        var from = $('#interpretModal_from').val();
        var to = $('#interpretModal_to').val();
        var text_ru = $('#interpretModal_text_ru').val();
        var text_kz = $('#interpretModal_text_kz').val();
        var assessment = $('#interpretModal_assessment').val();

        var data = {
            action: action,
            quiz_id: quiz_id,
            from: from,
            to: to,
            text_ru: text_ru,
            text_kz: text_kz,
            assessment: assessment,
        }
        console.log(data);
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "POST",
          url: "/admin/test/interpret/add",
          data: data,
          dataType: 'json',
          success: function (data) {
            console.log(data);        
            fill_interpret(quiz_id);
          },
          error: function (data) {
            console.log('ERR');
              console.log(data);
          },
      }); 

      });    
      /*-------- END Add interpretation ------------------*/

   /*-------- DELETE Interpret ------------------*/

   jQuery('#interprets_table').on('click', '.delete_interpret', function() {
    var action = 'delete';
    var interpret_id = $(this).prev().val(); 
    $('#deleteInterpretModal_action').val(action);
    $('#deleteInterpretModal_interpret_id').val(interpret_id);
    $('#deleteInterpretModal').modal('show');

  });


  // delete question modal button click
  jQuery('#deleteInterpretModal_save').click(function () {
    $('#deleteInterpretModal').modal('hide');
    var quiz_id =$("#quiz_id").val();
    var action = $('#deleteInterpretModal_action').val();
    var interpret_id = $('#deleteInterpretModal_interpret_id').val();
    var data = {
        action: action,
        interpret_id: interpret_id,
    }
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      url: "/admin/test/interpret/delete",
      data: data,
      dataType: 'json',
      success: function (data) {
        console.log(data);        
        fill_interpret(quiz_id);
      },
      error: function (data) {
        console.log('ERR');
          console.log(data);
      },
  }); 

  });

  /*-------- END DELETE Interpret ------------------*/






/*------------------------------ Add QUIZ ----------------------------*/
jQuery('#add_quiz_button').click(function () {
        console.log('add quiz button clicked')
        $('#addQuizModal').modal('show');
        var action = 'add';
        $('#addQuizModal_action').val(action);
      });

    // Modal safe button click
      jQuery('#addQuizModal').on('click', '.modal_add', function() {
        
        $('#addQuizModal').modal('hide');
        var action = $('#addQuizModal_action').val();
        var title = $('#addQuizModal_title').val();
        var title_kz = $('#addQuizModal_title_kz').val();
        var desc = $('#addQuizModal_desc').val();
        var desc_kz = $('#addQuizModal_desc_kz').val();
        var inst = $('#addQuizModal_inst').val();
        var inst_kz = $('#addQuizModal_inst_kz').val();
        if (action != 'add') { return; }

        var data = {
            action: action,
            title: title,
            title_kz: title_kz,
            desc: desc,
            desc_kz: desc_kz,          
            inst: inst,
            inst_kz: inst_kz,
        }
        console.log(data);
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "POST",
          url: "/admin/test/quiz/add",
          data: data,
          dataType: 'json',
          success: function (data) {
            console.log(data);        
            fill_quizzes();
          },
          error: function (data) {
            console.log('ERR');
              console.log(data);
          },
      }); 

      });    
      /*-------- END Add QUIZ ------------------*/  

      function fill(quiz_id) {
        loading_content = "<div class='overlay dark'><i class='fas fa-2x fa-sync-alt'></i></div>";
        $('#question_table').parent().append(loading_content);
  
  
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "GET",
          url: "/admin/test/edit/table/"+quiz_id,
          dataType: 'html',
          success: function (data) {
              $('#example1').empty();
              $('#example1').append(data);
              $('.overlay').remove();
          },
          error: function (data) {
            console.log('ERR');
              console.log(data);
          },
      });
        
      };
      
      function fill_interpret(quiz_id) {
        loading_content = "<div class='overlay dark'><i class='fas fa-2x fa-sync-alt'></i></div>";
        $('#interpret_table').parent().append(loading_content);
  
  
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "GET",
          url: "/admin/test/edit/interpret/"+quiz_id,
          dataType: 'html',
          success: function (data) {
              $('#interprets_table').empty();
              $('#interprets_table').append(data);
              $('.overlay').remove();
          },
          error: function (data) {
            console.log('ERR');
              console.log(data);
          },
      });
        
      };      


      function fill_quizzes() {
        loading_content = "<div class='overlay dark'><i class='fas fa-2x fa-sync-alt'></i></div>";
        $('#quiz_table').parent().append(loading_content);
  
  
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "GET",
          url: "/admin/test/quiz-table/",
          dataType: 'html',
          success: function (data) {
              $('#example1').empty();
              $('#example1').append(data);
              $('.overlay').remove();
          },
          error: function (data) {
            $('.overlay').remove();
            console.log('ERR');
              console.log(data);
          },
      });
        
      }; 

      jQuery('#fill_interpret').click(function () {
        fill_interpret(18);
      });
      
      
 
});//Dock ready