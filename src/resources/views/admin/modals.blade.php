
<!----------------- EDIT / ADD Question -------------->
<!-- Modal -->
<div class="modal fade" id="questionModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Добавление / изменение вопроса</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p></p>

            <!-- general form elements -->
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">{{ __("Вопрос") }}:</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="questionModal_form">
                      <div class="card-body">
                        <div class="form-group">
                          <label for="questionModal_question_text">{{ __("Текст вопроса") }} (Русский)</label>
                          <textarea class="form-control" id="questionModal_question_text" ></textarea>
                          <label for="questionModal_question_text_kz">{{ __("Текст вопроса") }} (Казахский)</label>
                          <textarea class="form-control" id="questionModal_question_text_kz" ></textarea>
                        </div>
                        <div class="form-group">
                          <label for="questionModal_question_description">{{ __("Описание вопроса") }} (Русский)<span class="text-muted"> ({{ __("не обязательно") }})</span></label>
                          <textarea class="form-control" id="questionModal_question_description" ></textarea>
                          <label for="questionModal_question_description_kz">{{ __("Описание вопроса") }} (Казахский)<span class="text-muted"> ({{ __("не обязательно") }})</span></label>
                          <textarea class="form-control" id="questionModal_question_description_kz" ></textarea>
                        </div>                        
                        <!-- Left column 
                        <div class="col-md-3"> -->
                        <!--</div> 
                         END Left column -->
                         <input type="hidden" id="questionModal_action" name="action">
                         <input type="hidden" id="questionModal_quiz_id" name="questionModal_quiz_id" value="">
                         <input type="hidden" id="questionModal_question_id" name="questionModal_question_id">
                      </div>
                      <!-- /.card-body -->

                      <div class="card-footer">

                      </div>
                    </form>
                  </div>
                  <!-- /.card -->

            </div>
            <div class="modal-footer justify-content-between">
              <button  type="button" class="btn btn-default" data-dismiss="modal">{{ __("Отмена") }}</button>
              <button id="questionModal_save" type="button" class="btn btn-primary">{{ __("Сохранить") }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
<!-----------------END EDIT / ADD Question -------------->

<!----------------- EDIT / ADD ANSER -------------->
<!-- Modal -->
<div class="modal fade" id="answerModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __("Добавление / изменение ответа") }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p></p>

            <!-- general form elements -->
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">{{ __("Вопрос") }}: <span id="answerModal_qestion_text"></span></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="answerModal_form">
                      <div class="card-body">
                        <div class="form-group">
                          <label for="answerModal_answer_text">{{ __("Текст ответа") }} (русский)</label>
                          <input type="text" class="form-control" id="answerModal_answer_text" >
                          <label for="answerModal_answer_text_kz">{{ __("Текст ответа") }} (Казахский)</label>
                          <input type="text" class="form-control" id="answerModal_answer_text_kz" >
                        </div>
                        <!-- Left column 
                        <div class="col-md-3"> -->
                          <div class="form-group">
                            <label for="answerModal_answer_scope">{{ __("Бал за ответ") }}</label>
                            <input type="number" class="form-control" id="answerModal_answer_scope" value="0">
                          </div>
                        <!--</div> 
                         END Left column -->
                         <input type="hidden" id="answerModal_action" name="action">
                         <input type="hidden" id="answerModal_answer_id" name="answerModal_answer_id">
                         <input type="hidden" id="answerModal_question_id" name="answerModal_question_id">
                      </div>
                      <!-- /.card-body -->

                      <div class="card-footer">

                      </div>
                    </form>
                  </div>
                  <!-- /.card -->

            </div>
            <div class="modal-footer justify-content-between">
              <button  type="button" class="btn btn-default" data-dismiss="modal">{{ __("Отмена") }}</button>
              <button id="answerModal_save" type="button" class="btn btn-primary">{{ __("Сохранить") }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
<!----------------- EDIT / ADD ANSER -------------->


<!----------- DELETE Question ---------------------->

<!-- Modal -->
<div class="modal fade" id="deleteQuestionModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content bg-danger">
            <div class="modal-header">
              <h4 class="modal-title">{{ __("Удалить вопрос") }}?</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                
              </button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="deleteQuestionModal_action" name="action">  
            <input type="hidden" id="deleteQuestionModal_question_id" name="deleteQuestionModal_question_id">
            <input type="hidden" id="deleteQuestionModal_quiz_id" name="deleteQuestionModal_quiz_id">
            {{ __("Удаление безвозвратно, продолжить") }}?
            </div>
            <div class="modal-footer justify-content-between">
              <button  type="button" class="btn btn-success" data-dismiss="modal">{{ __("Отмена") }}</button>
              <button id="deleteQuestionModal_save" type="button" class="btn btn-warning">{{ __("Да") }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

<!----------- END DELETE Question ------------------>



<!----------- DELETE Anser ---------------------->

<!-- Modal -->
<div class="modal fade" id="deleteAnswerModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content bg-danger">
            <div class="modal-header">
              <h4 class="modal-title">{{ __("Удалить ответ") }}?</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                
              </button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="deleteAnswerModal_action" name="action">   
            <input type="hidden" id="deleteAnswerModal_question_id" name="deleteAnswerModal_question_id">
            <input type="hidden" id="deleteAnswerModal_quiz_id" name="deleteAnswerModal_quiz_id">
            <input type="hidden" id="deleteAnswerModal_answer_id" name="deleteAnswerModal_answer_id">
            {{ __("Удаление безвозвратно, продолжить") }}?
            </div>
            <div class="modal-footer justify-content-between">
              <button  type="button" class="btn btn-success" data-dismiss="modal">{{ __("Отмена") }}</button>
              <button id="deleteAnswerModal_save" type="button" class="btn btn-warning">{{ __("Да") }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

<!----------- END DELETE Question ------------------>


<!----------------- ADD INTERPRETATION -------------->
<!-- Modal -->
<div class="modal fade" id="interpretModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __("Добавление интерпретации результата") }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p></p>

            <!-- general form elements -->
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title"><span id="answerModal_qestion_text"></span></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="answerModal_form">
                      <div class="card-body">
                        <div class="form-group">
                          <label for="interpretModal_from">{{ __("От") }}</label>
                          <input type="number" class="form-control" id="interpretModal_from" >
                        </div>
                        <!-- Left column 
                        <div class="col-md-3"> -->
                          <div class="form-group">
                            <label for="interpretModal_to">{{ __("До") }}</label>
                            <input type="number" class="form-control" id="interpretModal_to" value="0">
                          </div>
                          <div class="form-group">
                          <label for="interpretModal_text_ru">{{ __("Текст") }} RU</label>
                          <input type="text" class="form-control" id="interpretModal_text_ru" >

                          <label for="interpretModal_text_kz">{{ __("Текст") }} KZ</label>
                          <input type="text" class="form-control" id="interpretModal_text_kz" >                          
                        </div>  
                        <div class="form-group">
                            <label for="interpretModal_assessment">{{ __("Оценка состояния") }} <span class="">(1 - {{ __("хорошо") }}, 2 - {{ __("нормально") }}, 3 - {{ __("плохо") }})</span></label>
                            <input type="number" class="form-control" id="interpretModal_assessment" value="0">
                          </div>                                              
                        <!--</div> 
                         END Left column -->
                         <input type="hidden" id="interpretModal_action" name="action">
                         <input type="hidden" id="interpretModal_id" name="interpretModal_interpret_id">
                      </div>
                      <!-- /.card-body -->

                      <div class="card-footer">

                      </div>
                    </form>
                  </div>
                  <!-- /.card -->

            </div>
            <div class="modal-footer justify-content-between">
              <button  type="button" class="btn btn-default" data-dismiss="modal">{{ __("Отмена") }}</button>
              <button id="interpretModal_save" type="button" class="btn btn-primary modal_add">{{ __("Сохранить") }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
<!----------------- END ADD INTERPRETATION-------------->


<!----------- DELETE Question ---------------------->

<!-- Modal -->
<div class="modal fade" id="deleteInterpretModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content bg-danger">
            <div class="modal-header">
              <h4 class="modal-title">{{ __("Удалить вопрос") }}?</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                
              </button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="deleteInterpretModal_action" name="action">  
            <input type="hidden" id="deleteInterpretModal_interpret_id" name="deleteQuestionModal_question_id">
            <input type="hidden" id="deleteInterpretModal_quiz_id" name="deleteQuestionModal_quiz_id">
            {{ __("Удаление безвозвратно, продолжить") }}?
            </div>
            <div class="modal-footer justify-content-between">
              <button  type="button" class="btn btn-success" data-dismiss="modal">{{ __("Отмена") }}</button>
              <button id="deleteInterpretModal_save" type="button" class="btn btn-warning">{{ __("Да") }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

<!----------- END DELETE Question ------------------>