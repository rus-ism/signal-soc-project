
                  <thead>
                  <tr>
                    <th>№</th>
                    <th>{{ __("От") }}</th>
                    <th>{{ __("До") }}</th>   
                    <th>{{ __("Интерпретация") }}</th>                 
                    <th>{{ __("Оценка состояния") }} <p><small class="text-muted">(1 - {{ __("хорошо") }}, 2 - {{ __("нормально") }}, 3 - {{ __("плохо") }})</small></p></th>                                        
                  </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="6">

                                <button class="btn btn-block btn-outline-primary btn-xs add_interpret">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                {{ __("Добавить") }}
                                </button>
                  
                      </td>
                    </tr>                    
                   {{$i=1}}
                   @foreach ($interpretations as $interpret)
                    <tr>                      
                      <td>{{$i}}</td>
                      <td>{{$interpret->from}}</td>
                      <td>{{$interpret->to}}</td>
                      <td>{{$interpret->text}}</td>
                      <td>{{$interpret->assessment}}</td>
                      <td>
                        <input type="hidden" name="delete_answer" value="{{$interpret->id}}">
                        <i class="fas fa-trash delete_interpret" style="cursor:pointer; font-size:15px; color:red"> </i>                        
                      </td>
                    </tr>  
                    {{$i++}}                  
                   @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
  
                  </tr>
                  </tfoot>