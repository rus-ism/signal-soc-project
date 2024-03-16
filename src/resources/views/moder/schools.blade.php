<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <title>{{ __("Школы") }}</title>
<!-- Test FTP-->
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

      <li class="nav-item d-none d-sm-inline-block">
        <p class="text-info">{{ __("Модератор") }} ({{$region->name}})</p>
      </li>
    </ul>
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
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('moder.leftside')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __("Школы") }}</h1>
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





      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"></h3>


        </div>
        <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __("Школа") }}</th>
                    <th>{{ __("контингент") }}</th>
                    <th>{{ __("Респондентов") }}</th>         
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
									@if (count($schools) > 0)
									@foreach ($schools as $school)    
                
                  
                  <?php #echo('<br>--------<br>'); var_dump($tutor); echo('<br>--------<br>') ?>
                                   
                 
                    
                      <tr>
                        <td>{{$school['school']->name}}</td>
                        <td></td>
                        <td></td>
                        <td>
                          <a id="{{$school['school']->id}}" class="rename_school btn btn-app">
                            <i class="fas fa-edit"></i>
                          </a>                          
                        </td>

                      </tr>

									@endforeach
									
									@else
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endif          
                  </tbody>
                  <tfoot>
                  <tr>
                  <tr>
                    <th></th>
                    <th></th>           
                    <th></th>            
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

<!-- Modal: Grade input -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __("Введите новые наименование") }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form  id="stud_count_form">
        <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("Наименование на казахском") }}</label>
                    <input type="text" class="form-control stud_count_input" id="name_kz" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("Наименование русском") }}</label>
                    <input type="text" class="form-control stud_count_input" id="name_ru" placeholder="">
                  </div>                  
                  <input type="hidden" value="" id="rename_school_school_id">

                </div>
                <!-- /.card-body -->

                <div class="card-footer">

                </div>          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="rename_school_close" class="btn btn-secondary" data-dismiss="modal">{{ __("Отмена") }}</button>
        <button type="button" id="rename_school_save" class="btn btn-primary">{{ __("Сохранить") }}</button>
      </div>
    </div>
  </div>
</div>

<!-- END Modal: Grade input -->
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/adm/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/adm/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/adm/dist/js/adminlte.min.js"></script>
<script src="/js/moderator.js"></script>
<!-- AdminLTE for demo purposes 
<script src="/adm/dist/js/demo.js"></script>-->
</body>
</html>
