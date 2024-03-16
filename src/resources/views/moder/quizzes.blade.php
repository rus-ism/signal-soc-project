<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
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
            <h1>{{ __("Тесты") }} \ {{ __("Анкеты") }}</h1>
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
                    <td><a href="/moderator/test/{{$quiz->id}}">{{$quiz->quiz_name}}</a></td>
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
<!-- AdminLTE for demo purposes 
<script src="/adm/dist/js/demo.js"></script>-->
</body>
</html>
