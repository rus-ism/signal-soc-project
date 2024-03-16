<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Тесты</title>

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
            <h1>{{ __("Результаты анкетирования") }}</h1>
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

    <div class="card">
            <div class="card-header">
                <h1 class="card-title">{{ __("Общая информация") }}</h1>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">{{ __("Контингент") }}</span>
                                <span class="info-box-number text-center text-muted mb-0">{{$all_student_count}} {{ __("человек") }}</span>
                                </div>
                            </div>
                            </div>

                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">{{ __("Всего респондентов") }}</span>
                                <span class="info-box-number text-center text-muted mb-0">{{$anketed_respondents}} {{ __("человек") }}</span>
                                </div>
                            </div>
                            </div>                             

                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">{{ __("Процент прошедших тестирование") }}</span>
                                <span class="info-box-number text-center text-muted mb-0">
                                   <span class="text-info">{{$anketed_pircent}} % {{ __("от контингента") }}</span></span>
                                </div>
                            </div>
                            </div>

                           

                        </div>

                    </div>
                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                        <p class="text-muted"></p>
                        <br>
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
          <h3 class="card-title"></h3>


        </div>
        <div class="card-body">
        <table id="results" class="table table-bordered table-striped table-hover">
                  <thead>

                    <th rowspan="2">{{ __("Тест") }}</th>
                    <th rowspan="2">{{ __("Классы") }}</th>
                    <th rowspan="2">{{ __("Пройдено всего") }}</th>
                    <th colspan="3"><span style="margin:auto">{{ __("Количество по оценке состояния") }}</span></th>

                    <th rowspan="2">{{ __("Подробнее") }}</th>
                    <tr>
                      <th>{{ __("Хорошо") }}</th>                      
                      <th>{{ __("Нормально") }}</th>
                      <th>{{ __("Плохо") }}</th>                      
                    </tr>
                  </thead>
                    
                  <tbody>
									@if (count($quizzes_array) > 0)
									@foreach ($quizzes_array as $quiz)              
                  <!-- <tr class='clickable-row' data-href='/' onclick="window.location='/'"> -->
                  <tr>
                    <td>{{$quiz['quiz']->quiz_name}}</td>
                    <td>
                      @foreach ($quiz['quiz']->quizacl()->get('grade') as $grd)
                        {{$grd['grade']}} &nbsp  
                      @endforeach
                    </td>
                    <td>{{$quiz['count']}}</td>   
                    
                    @foreach($quiz['ranges'] as $range)
                      <td>{{$range}}</td>              
                    @endforeach
                    <td><a href="/admin/results/{{$quiz['quiz']->id}}">{{ __("подробнее") }} ...</a></td>
                  </tr>
									@endforeach								
									@else
                  <tr>
											<td colspan="2" style="text-align:center">
												<span>{{ __("Нет тестов") }}</span>
											</td>
										</tr>
									@endif                  
                  </tbody>
                  <tfoot>
                    <th colspan="2">{{ __("Итого") }}:</th>
                    <th>{{$all_results}}</th>
                    @foreach ($all_ranges as $ar)
                      <th>{{$ar}}</th>
                    @endforeach                    
                    <th></th>
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
