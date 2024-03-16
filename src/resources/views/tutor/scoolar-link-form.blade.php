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
          </li>               -->
               
               


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
            <h2></h2>
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

            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">{{ __("Принять ученика") }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="link_schoolar_form" method="post" action="/tutor/schoolar/link">
              @csrf
                <div class="card-body">


                  <div class="form-group">
                    <label for="igrade">{{ __("ИКТ - ФИО") }}</label>
                    <select class="custom-select form-control-border" id="user_id"  type="text" name="user_id" placeholder="">
                    @foreach ($profiles as $profile)
                    <? echo $profile;?>
                      <option  value="{{$profile->user_id}}">{{$profile->user()->first()->email}} - {{$profile->user()->first()->name}}</option>
                    @endforeach
                    </select>
                  </div>



                  
                  <input type="hidden" name="action" value="link">
                </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button id="student_link_submint" type="submit" class="btn btn-primary">{{ __("Сохранить") }}</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

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
