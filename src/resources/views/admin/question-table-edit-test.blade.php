

                  <thead>
                  <tr>
                    <th width="50%">{{ __("Вопрос") }}</th>
                    <th width="20px">{{ __("Тип вопроса") }}</th>
                    <th width="10px"></th>
                    <th></th>
                    <th>{{ __("Ответы") }}</th>   
                    <th width="30px">{{ __("Бал за ответ") }}</th>                 
                    <th width="10px">{{ __("Удалить") }}</th>
                    <th width="10px">{{ __("Редактировать") }}</th>                                        
                  </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="8">                        
                              <button class="btn btn-block btn-outline-primary btn-xs add_question">
                              <i class="fa fa-plus" aria-hidden="true"></i>
                              {{ __("Добавить вопрос") }}
                              </button>                  
                      </td>
                    </tr>
                  @if ($questions_count > 0) 
                    @foreach ($quiz_array['questions'] as $question)
                      <tr>
                        <td class="cell_question_text{{$question['question']->text}}" rowspan="{{$question['answers_count']}}">
                          {{$question['question']->text}}
                        </td>
                        <td class="cell_type_question_id{{$question['question']->question_type_id}}" rowspan="{{$question['answers_count']}}">{{$question['question']->question_type_id}}</td>
                        <td rowspan="{{$question['answers_count']}}">
                          <p>
                              <a style ="cursor:pointer">
                                <input type="hidden" name="quest_id" value="{{$question['question']->id}}">
                                <i class="fas fa-trash delete_question" style="font-size:15px; color:red"></i>
                              </a>
                          </p>
                          <p>
                            <a style ="cursor:pointer">
                              <input type="hidden" name="quest_id" value="{{$question['question']->id}}">
                              <i class="fas fa-edit edit_question" style="font-size:15px; color:#C1A81B"> </i>
                            </a>
                          </p>
                        </td>
                        <td rowspan="{{$question['answers_count']}}">
                          
                        </td>
                      </tr>
                      @foreach ($question['answers'] as $answer)
                        <tr>
                          <td class="cell_answer_text{{$answer->id}}">{{$answer->text}}</td>
                          <td class="cell_answer_scope{{$answer->id}}">{{$answer->scope}}</td>
                          <td>
                            <input class="answer_text_{{$answer->id}}" type="hidden" name="answer_text" value="{{$answer->text}}">
                            <input type="hidden" name="quest_text" value="{{$question['question']->text}}">
                            <input type="hidden" name="quest_id" value="{{$question['question']->id}}">
                            <input type="hidden" name="delete_answer" value="{{$answer->id}}">
                            <i class="fas fa-trash delete_answer" style="cursor:pointer; font-size:15px; color:red"> </i>
                          </td>
                          <td>
                            <input type="hidden" name="quest_text" value="{{$question['question']->text}}">
                            <input type="hidden" name="quest_id" value="{{$question['question']->id}}">
                            <input type="hidden" name="edit_answer" value="{{$answer->id}}">
                            <i class="fas fa-edit edit_answer" style="cursor:pointer; font-size:15px; color:#C1A81B" data-toggle="modal" data-target="#exampleModal"> </i>                           
                          </td>
                        </tr>       
                      @endforeach   
                        <tr style="border-bottom: 2px solid black">
                          <td colspan="4">
                              <input type="hidden" name="add_answ_quest_text" value="{{$question['question']->text}}">
                              <input type="hidden" name="add_answ_quest_id" value="{{$question['question']->id}}">
                              <button class="btn btn-block btn-outline-primary btn-xs add-answer">                              
                              <i class="fa fa-plus" aria-hidden="true"></i>
                              {{ __("Добавить ответ") }}
                              </button>
                          </td>
                        </tr> 
                        <!--
                        <tr>
                          <td style="border-bottom: 2px solid black" colspan="6">dgs</td>
                        </tr>        
                      -->
                    @endforeach
                  @endif
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>{{ __("Вопрос") }}</th>
                    <th>{{ __("Тип вопроса") }}</th>
                    <th></th>
                    <th></th>
                    <th>{{ __("Ответы") }}</th>   
                    <th>{{ __("Бал за ответ") }}</th>                 
                    <th>{{ __("Удалить") }}</th>
                    <th>{{ __("Редактировать") }}</th>  
                  </tr>
                  </tfoot>

