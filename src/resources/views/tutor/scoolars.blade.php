<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <title>{{ __("Главная") }}</title>

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
    <ul class="navbar-nav">
      <li class="nav-item">
        <p class="text-info"> </p>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
          <!-- Lang -->
          @include('lang-switcher')       
      <li class="nav-item">
        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        {{ __("Выйти") }}
				</a>
        <form id="logout-form" action="/logout" method="POST">
								@csrf
				</form>        
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->


    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->


      <!-- SidebarSearch Form -->


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library 


               <li class="nav-item">
            <a href="/tutor" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Общая информация

              </p>
            </a>
          </li>               
               -->
               


          <li class="nav-item">
            <a href="/tutor/tests" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
              {{ __("Тесты") }}

              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/tutor/schoolar" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
              {{ __("Ученики") }}

              </p>
            </a>
          </li>                
      
    
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
            <h2>{{ __("Психолог") }}: {{$school->name}}</h2>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">

            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
      

      <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __("Ученики") }}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
            
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th></th>
                    <th>{{ __("Индивидуальный код") }}</th>
                    <th>{{ __("ФИО") }}</th>
                    <th>{{ __("Класс") }}</th>
                    <th>{{ __("Пройдены анкеты") }}</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($schoolars as $schoolar) 
                
                    <tr>
                      <td>  
                      @if ($schoolar['profile']->grade == 6)
                        <input type="hidden" name="user_id" value="{{$schoolar['profile']->user_id}}">
                        <input type="hidden" name="profile_id" value="{{$schoolar['profile']->id}}">
                        <i class="fas fa-backward revert5_student" style="cursor:pointer; font-size:15px; color:#C1A81B"> Вернуть в 5 класс </i>     
                      @endif          
                      @if ($schoolar['profile']->grade == 5)
                        <input type="hidden" name="user_id" value="{{$schoolar['profile']->user_id}}">
                        <input type="hidden" name="profile_id" value="{{$schoolar['profile']->id}}">
                        <i class="fas fa-backward revert6_student" style="cursor:pointer; font-size:15px; color:#C1A81B"> Вернуть в 6 класс </i>     
                      @endif                            
                      </td>
                      <td>{{$schoolar['profile']->user()->first()->email}}</td>
                      <td>{{$schoolar['profile']->fio}}</td>
                      <td>{{$schoolar['profile']->grade}} {{$schoolar['profile']->litera}}</td>
                      <td>
                          @if (count($schoolar['results']) > 0)
                            @foreach ($schoolar['results'] as $result)         
                              <!-- No need to show the fio 
                              <a href="/tutor/result/{{$result->id}}/{{$result->quiz()->first()->id}}">{{$result->quiz()->first()->quiz_name}}</a><br> 
                              -->
                               <a href="#">{{$result->quiz()->first()->quiz_name}}</a><br>                      
                              
                            @endforeach
                          @endif
                      </td>
                      <td>                      
                      <input id="litera_{{$schoolar['profile']->user_id}}" type="hidden" name="litera" value="{{$schoolar['profile']->litera}}">
                      <input type="hidden" name="grade" value="{{$schoolar['profile']->grade}}">
                      <input type="hidden" name="fio" value="{{$schoolar['profile']->fio}}">
                      <input type="hidden" name="user_id" value="{{$schoolar['profile']->user_id}}">
                      <i class="fas fa-edit edit_student" style="cursor:pointer; font-size:15px; color:#C1A81B" data-toggle="modal" data-target="#exampleModal"> </i>

                      <input type="hidden" name="user_id" value="{{$schoolar['profile']->user_id}}">
                      <i class="fas fa-trash delete_student" style="cursor:pointer; font-size:15px; color:#C1A81B" data-toggle="modal"> </i>

                      <input type="hidden" name="user_id" value="{{$schoolar['profile']->user_id}}">
                      <i class="fas fa-unlink unlink_student" style="cursor:pointer; font-size:15px; color:#C1A81B" data-toggle="modal"> </i>                      
                      </td>
                    </tr>
                  @endforeach		
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>{{ __("Индивидуальный код") }}</th>
                    <th>{{ __("ФИО") }}</th>
                    <th>{{ __("Класс") }}</th>
                    <th>{{ __("Пройдены анкеты") }}</th>
                    <th></th>

                  </tr>
                  </tfoot>
                </table>
                
              </div>
              <form id="tutor_add_form" action="/tutor/schoolar/add" method="post">
              @csrf
                <input type="hidden" name="action" value="show_form">
                <button type="submit" class="btn btn-block btn-primary btn-lg">{{ __("Добавить") }}</button>
              </form>
              <p></p>
              <form id="tutor_link_form" action="/tutor/schoolar/link" method="post">
              @csrf
                <input type="hidden" name="action" value="show_link_form">
                <button type="submit" class="btn btn-block btn-primary btn-lg">{{ __("Принять с другой школы") }}</button>
              </form>              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->      

      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">



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


<!----------------- EDIT / ADD ANSER -------------->
<!-- Modal -->
<div class="modal fade" id="editStudent" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __("Редактирование данных ученика") }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p></p>

            <!-- general form elements -->
                  <div class="card card-primary">

                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editStudent_form">
                      <div class="card-body">
                        <div class="form-group">
                          <label for="editStudent_fio">{{ __("ФИО") }}</label>
                          <input type="text" class="form-control" id="editStudent_fio" >
                        </div>
                        <!-- Left column 
                        <div class="col-md-3"> -->
                          <div class="form-group">
                            <label for="editStudent_grade">{{ __("Класс") }}</label>
                            <input type="number" class="form-control" id="editStudent_grade" value="">
                          </div>
                          <div class="form-group">
                            <label for="editStudent_litera">{{ __("Литера") }}</label>
                            <input type="text" class="form-control" id="editStudent_litera" value="">
                          </div>                          
                        <!--</div> 
                         END Left column -->
                         <input type="hidden" id="editStudent_user_id" name="user_id">
                         <input type="hidden" id="editStudent_action" name="action">
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
              <button id="editStudent_save" type="button" class="btn btn-primary">{{ __("Сохранить") }}</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
<!----------------- EDIT / ADD ANSER -------------->





<!-- AdminLTE for demo purposes 
<script src="/adm/dist/js/demo.js"></script>-->





<!-- jQuery -->
<script src="/adm/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/adm/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/adm/dist/js/adminlte.min.js"></script>
<script src="/js/tutor.js"></script>
<!-- AdminLTE for demo purposes 
<script src="/adm/dist/js/demo.js"></script>-->
</body>
</html>
