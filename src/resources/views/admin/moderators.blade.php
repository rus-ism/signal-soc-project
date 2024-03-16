<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ __("Тесты") }}</title>

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
            <h1>{{ __("Тесты") }} \ {{ __("анкеты") }}</h1>
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
          <h3 class="card-title">
          {{ __("Запросы на модерацию") }}
          </h3>
        </div>
        <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __("Район") }}</th>
                    <th>{{ __("ФИО") }}</th>                    
                    <th>{{ __("Дата регистрации") }}</th>
                    <th>{{ __("Подтвердить") }}</th>
                  </tr>
                  </thead>
                  <tbody>
					@if ($user_requests->count() > 0)
					@foreach ($user_requests as $request)                    
                  <tr>
                    <td>{{$request->region()->first()->name}}</td>
                    <td>{{$request->user()->first()->name}}</td>
                    <td>{{$request->created_at}}</td>

                    <td>
                        <div class="custom-control custom-checkbox ">
                          <input class="custom-control-input moderator_accept" type="checkbox" id="rq{{$request->id}}" value="{{$request->id}}">
                          <label for="rq{{$request->id}}" class="custom-control-label"></label>                                                  
                        </div>
                    </td>

                  </tr>
					@endforeach									
					@else
                  <tr>
					<td colspan="3" style="text-align:center">
						<span>{{ __("Нет запросов") }}</span>
					</td>
				  </tr>
					@endif                  
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>{{ __("Район") }}</th>
                    <th>{{ __("ФИО") }}</th>                    
                    <th>{{ __("Дата регистрации") }}</th>
                    <th>{{ __("Подтвердить") }}</th>
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
          <h3 class="card-title">
          {{ __("Модераторы") }}
          </h3>
        </div>
        <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __("Район") }}</th>
                    <th>{{ __("ФИО") }}</th>                    
                    <th>{{ __("Дата регистрации") }}</th>
                  </tr>
                  </thead>
                  <tbody>
					@if ($users->count() > 0)
					@foreach ($users as $user)                    
                  <tr>
                   
                    <td></td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->created_at}}</td>



                  </tr>
					@endforeach									
					@else
                  <tr>
					<td colspan="3" style="text-align:center">
						<span>{{ __("Нет модераторов") }}</span>
					</td>
				  </tr>
					@endif                  
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>{{ __("Район") }}</th>
                    <th>{{ __("ФИО") }}</th>                    
                    <th>{{ __("Дата регистрации") }}</th>
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

<!-- jQuery -->
<script src="/adm/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/adm/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/adm/dist/js/adminlte.min.js"></script>

<script src="/adm/dist/js/admin-moderator.js"></script>
<!-- AdminLTE for demo purposes 
<script src="/adm/dist/js/demo.js"></script>-->
</body>
</html>
