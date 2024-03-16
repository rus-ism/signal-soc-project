
                  <thead>
                  <tr>
                    <th>{{ __("Тест") }}</th>
                    <th>{{ __("Описание") }}</th>                    
                    <th>{{ __("Публичный") }} \ {{ __("закрытый доступ") }}</th>
                    <th>{{ __("Для классов") }}:</th>
                    <th>{{ __("Количество вопросов") }}</th>
                    <th>{{ __("Тип теста") }}</th>
                  </tr>
                  </thead>
                  <tbody>
									@if (count($quizzes) > 0)
									@foreach ($quizzes as $quiz)                    
                  <tr>
                    <td><a href="/admin/test/edit/{{$quiz->id}}"> {{$quiz->quiz_name}}</a></td>
                    <td>{{$quiz->quiz_description}}</td>
                    <td>{{ __("Публичный") }}</td>
                    <td>
                      @foreach ($quiz->quizacl()->get('grade') as $grd)
                        {{$grd['grade']}} &nbsp  
                      @endforeach
                    </td>
                    <td>{{$quiz->question()->get()->count()}}</td>
                    <td>{{$quiz->type_id}}</td>
                  </tr>
									@endforeach
									
									@else
                  <tr>
											<td colspan="5" style="text-align:center">
												<span>{{ __("Нет тестов") }}</span>
											</td>
										</tr>
									@endif                  
                  </tbody>
                  <tfoot>
                  <tr>
                  <th>{{ __("Тест") }}</th>
                    <th>{{ __("Описание") }}</th>                    
                    <th>{{ __("Публичный") }} \ {{ __("закрытый доступ") }}</th>
                    <th>{{ __("Количество вопросов") }}</th>
                    <th>{{ __("Тип теста") }}</th>
                  </tr>
                  </tfoot>