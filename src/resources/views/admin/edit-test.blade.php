<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <title>Редактор: {{$quiz->quiz_name}}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/adm/plugins/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="/adm/plugins/select2/css/select2.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/adm/dist/css/adminlte.min.css">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
</head>
<body class="hold-transition sidebar-mini">

@include('admin.modals')


<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

    <!-- Lang -->
    @include('lang-switcher')


      <li class="nav-item">
        <a class="nav-link" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        {{ __("Выйти") }}
				</a>
        <form id="logout-form" action="/logout" method="POST">
								@csrf
				</form>        
      </li>
    </ul>

    <!-- Right navbar links -->

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->


    <!-- Sidebar -->
      <!-- Sidebar -->
      @include('admin.sidebar')  
    <!-- /.sidebar -->
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __("Редактор теста:") }} {{$quiz->quiz_name}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">

            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <!-- INPUT DATA -->
    <input type="hidden" id="quiz_id" name="quiz_id" value="{{$quiz->id}}">

    <div class="card card-default">
        <div class="card-header">
          <h2 class="card-title">
          {{ __("Классы, которым доступна анкета") }}
          </h2>
        </div>
        <div class="card-body">
          
            <div class="row">
                   <div class="col-sm-2"> 
                      <!-- checkbox -->
                      @for ($i = 1; $i < 7; $i++)
                      <?php
                        $checked = '';
                        foreach($grades as $grade) 
                        {
                          if ($grade->grade == $i)                        
                          {
                            $checked = 'checked';
                          }                        
                        }
                        ?>

                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input check-grade-edit" name="grade" type="checkbox" id="grade{{$i}}" value="{{$i}}" {{$checked}}>
                          <label for="grade{{$i}}" class="custom-control-label">{{$i}} {{ __("класс") }}</label>
                        </div>
                      </div>
                      @endfor
                    </div> 

                    <div class="col-sm-2"> 
                      <!-- checkbox -->
                      @for ($i = 7; $i < 12; $i++)
                      <?php
                        $checked = '';
                        foreach($grades as $grade) 
                        {
                          if ($grade->grade == $i)                        
                          {
                            $checked = 'checked';
                          }                        
                        }
                        ?>                      
                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input check-grade-edit" name="grade" type="checkbox" id="grade{{$i}}" {{$checked}} value="{{$i}}">
                          <label for="grade{{$i}}" class="custom-control-label">{{$i}} {{ __("класс") }}</label>
                        </div>
                      </div>
                      @endfor
                    </div> 

             </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">

        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->  


      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">
          {{$quiz->quiz_name}}
          </h2>
        </div>


        

        <div id="question_table" class="card-body">
          
        <table id="example1" class="table  table-hover">
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
                    <th></th>
                  </tr>
                  </tfoot>


         </table>
        </div>


        <!-- /.card-body -->
        <div class="card-footer">

        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

      <div class="card">
        <div class="card-header">
          <h2 class="card-title">
          {{ __("Интерпретация результатов для анкеты:") }} {{$quiz->quiz_name}}
          </h2>
        </div>
        <div class="card-body" id="interpret_table_div">
        <table id="interprets_table" class="table  table-hover">
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
                </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->      

    


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
    
    </div>
    
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">  
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"></button>
          <h4 class="modal-title">Header</h4>
        </div>
        <div class="modal-body">
          <p>Bootstrap 4.</p>
        </div>
        <div class="modal-footer">
		   <button type="button" class="btn btn-info">Save changes</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div>
</div>





<!-- jQuery -->
<script src="/adm/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/adm/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/adm/dist/js/adminlte.min.js"></script>

<script src="/adm/plugins/select2/js/select2.full.js"></script>


<script src="/adm/dist/js/admin-moderator.js"></script>
<script src="/adm/dist/js/admin-test.js"></script>
<!-- AdminLTE for demo purposes 
<script src="/adm/dist/js/demo.js"></script>-->
</body>
</html>
