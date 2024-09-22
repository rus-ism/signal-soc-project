<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ __("Техническое обслуживание базы") }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/adm/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/adm/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
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
            <h1>{{ __("Техническое обслуживание базы") }}</h1>
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


<!----------------- ADD QUIZ -------------->
<!-- Modal -->
<div class="modal fade" id="addQuizModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __("Добавить анкету") }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p></p>

            <!-- general form elements -->
                  <div class="card card-primary">
                    <div class="card-header">
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="answerModal_form">
                      <div class="card-body">
                        <div class="form-group">
                          <label for="addQuizModal_title">{{ __("Название") }} (Русский)</label>
                          <input type="text" class="form-control" id="addQuizModal_title" >

                          <label for="addQuizModal_title_kz">{{ __("Название") }} (Казахский)</label>
                          <input type="text" class="form-control" id="addQuizModal_title_kz" >                          
                        </div>

                        <div class="form-group">
                          <label for="addQuizModal_desc">{{ __("Описание") }} (Русский)<span class="text-muted"></span></label>
                          <textarea class="form-control" id="addQuizModal_desc" ></textarea>

                          <label for="addQuizModal_desc_kz">{{ __("Описание") }} (Казахский)<span class="text-muted"></span></label>
                          <textarea class="form-control" id="addQuizModal_desc_kz" ></textarea>                          
                        </div>  

                        <div class="form-group">
                          <label for="addQuizModal_inst">{{ __("Инструкция") }} (Русский)<span class="text-muted"></span></label>
                          <textarea class="form-control" id="addQuizModal_inst" ></textarea>

                          <label for="addQuizModal_inst_kz">{{ __("Инструкция") }} (Казахский)<span class="text-muted"></span></label>
                          <textarea class="form-control" id="addQuizModal_inst_kz" ></textarea>
                        </div>                          
                                             
                        <!--</div> 
                         END Left column -->
                         <input type="hidden" id="addQuizModal_action" name="action">
                         <input type="hidden" id="addQuizModal_id" name="addQuizModal_id">
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
              <button id="addQuizModal_save" type="button" class="btn btn-primary modal_add">{{ __("Сохранить") }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
<!----------------- END ADD QUIZ -------------->    

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"></h3>


        </div>
        <div class="card-body" id="quiz_table">
        <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __("Тест") }}</th>
                    <th>{{ __("Выполнить") }} </th>                    
                  </tr>
                  </thead>
                  <tbody>
									@if (count($quizzes) > 0)
									@foreach ($quizzes as $quiz)                    
                  <tr>
                    <td><a href="/admin/test/edit/{{$quiz->id}}"> {{$quiz->quiz_name}}</a></td>
                    <td><a href="/admin/service/delete_dublicates/{{$quiz->id}}">{{__("Удалить дубликаты")}}</a></td>
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
                    <th>{{ __("") }}</th>                    

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


<!-- Inc class -->
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"></h3>


        </div>
        <div class="card-body" id="quiz_table">
          <a href="/admin/service/grade_transfer/5">Из 5 в 6</a><br>
          <a href="/admin/service/grade_transfer/6">Из 6 в 7</a><br>
          <a href="/admin/service/grade_transfer/7">Из 7 в 8</a><br>
          <a href="/admin/service/grade_transfer/8">Из 8 в 9</a><br>
          <a href="/admin/service/grade_transfer/9">Из 9 в 10</a><br>
          <a href="/admin/service/grade_transfer/10">Из 10 в 11</a><br>
          <a href="/admin/service/grade_transfer/11">Из 11 в 12</a><br>
          <a href="/admin/service/graduation">Выпуск</a><br>
          <a href="/admin/service/update_respondent_grade?update_grade=1">Обновить клас Респондентов</a><br>
          <a href="/service/git-check">Git check 2</a><br>
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

<!-- jQuery -->
<script src="/adm/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/adm/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/adm/dist/js/adminlte.min.js"></script>

<script src="/adm/dist/js/admin-test.js"></script>
<!-- AdminLTE for demo purposes 
<script src="/adm/dist/js/demo.js"></script>-->


<script src="/adm/dist/js/adminlte.min.js"></script>
</body>
</html>
