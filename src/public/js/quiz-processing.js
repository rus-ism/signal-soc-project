jQuery(document).ready(function($){






    
    $('.form-check-input').on('click', function(evt) {
      var ofset = $(this).offset().top + 100;
      console.log(ofset);
    //  $('html, body').animate({
    //    scrollTop: ofset 
    //    }, 1000);
    });

    jQuery('#back-btn').click(function () {
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    

      var answer_val = $("input[name='answer']:checked").val();
      var result_id_val = $("input[name='result_id']").val();
      var question_list_id_val = $("input[name='question_list_id']").val();
      var quizprocessing_id = $("input[name='quizprocessing_id']").val();
      var result_id = $("input[name='result_id']").val();
      if (answer_val) {
        var answer = answer_val
      } else {
        answer = '0';
      }
      var action = 'next';
      var formData = {
        ans: answer,
        act: action,
        result_id: result_id_val,
        question_list_id: question_list_id_val,
        quizprocessing_id: quizprocessing_id,
      };
      console.log(formData);
    $.ajax({
        type: "POST",
        url: "/testing/back",
        data: formData,
        dataType: 'json',
        success: function (data) {
          console.log(data);

           NewQuestionFill(data);

        },
        error: function (data) {
          console.log('ERR');
            console.log(data);
        }
    });
    
    });

    jQuery('#next-btn').click(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        

          var answer_val = $("input[name='answer']:checked").val();
          var result_id_val = $("input[name='result_id']").val();
          var question_list_id_val = $("input[name='question_list_id']").val();
          var quizprocessing_id = $("input[name='quizprocessing_id']").val();
          var result_id = $("input[name='result_id']").val();
          if (answer_val) {
            var answer = answer_val
          } else {
            answer = '0';
          }
          var action = 'next';
          var formData = {
            ans: answer,
            act: action,
            result_id: result_id_val,
            question_list_id: question_list_id_val,
            quizprocessing_id: quizprocessing_id,
          };
          console.log(formData);
        $.ajax({
            type: "POST",
            url: "/testing/next",
            data: formData,
            dataType: 'json',
            success: function (data) {
              console.log('start fill');
              console.log(data);
               NewQuestionFill(data);
            
            },
            error: function (data) {
               console.log('error');
               console.log(data);
            }
        });
  
     });

     function NewQuestionFill(data) {
      console.log(data);
      jQuery('#question-bar').html('');
        data.question_lists.forEach( function(item){
          if (item.counter === data.quizprocessing.current) {
            var active = 'active';
          } else {
            var active = '';
          }
          if (item.answered === 1) {
            var btncolor = 'btn-primary';
          } else {
            var btncolor = 'btn-secondary';
          }
          //console.log(item.id);
          var str = `<button id="quest${item.id}" type="button" class="btn ${btncolor} question-list ${active}">${item.counter}</button>`;
          jQuery('#question-bar').append(str); 
        }); // end foreach
        jQuery('#question-title').html(`Вопрос: ${data.quizprocessing.current}`);
        jQuery('#question-text').html(`${data.question.text}`);

        $("input[name='result_id']").val('777777');

        jQuery('#answers-form').html('');
        data.answers.forEach( function(item){
            if (item.id == data.user_answered.answer_id) {
              var answered = 'checked';
            } else {
              answered = '';
            }
            console.log('ansewr id; '+data.user_answered.answer_id);
            var str = `
                      <label class="form-check">
                      <input class="form-check-input" type="radio" ${answered} value="${item.id}" name="answer">
                      <span class="form-check-label">
                        ${item.text}
                      </span>
                    </label>	            
            `;
            jQuery('#answers-form').append(str);
        });
        str2 = `
              <input id="result" type="hidden" name="result_id" value="${data.result.id}">
              <input id="question_list_id" type="hidden" name="question_list_id" value="${data.current_question_list.id}">
              <input id="quizprocessing_id" type="hidden" name="quizprocessing_id" value="${data.quizprocessing.id}">
            `;
        jQuery('#answers-form').append(str2);
        
     } //New question FILL

});//Dock ready