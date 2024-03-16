<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
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
        <p class="text-info">{{ __("Модератор") }} @if ($region) ({{$region->name}}) @else {{ __("Не назначен район") }} @endif </p>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
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
  @include('moder.leftside')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __("Доступ школ к тесту") }}. {{$region->name}}:</h1>
            <h12></h2>
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
                <h1 class="card-title">{{$quiz->quiz_name}}</h1>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                      <!-- 
                        <div class="row">
                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Всего пройдено</span>
                                <span class="info-box-number text-center text-muted mb-0">{{$quiz->respondent_result()->get()->count()}}</span>
                                </div>
                            </div>
                            </div>
                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Средний бал</span>
                                <span class="info-box-number text-center text-muted mb-0">{{$quiz->respondent_result()->get()->avg('scope')}}</span>
                                </div>
                            </div>
                            
                            </div>
                        
                        </div>

                        -->
                        <div class="row">
                          <h5 class="mt-4 mb-2">
                          {{ __("Интерпретация результатов") }}:      
                          </h5>                            

                        </div>
                    </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <p class="text-muted">{{$quiz->quiz_description}}</p>
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


      <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __("Доступ к анкетированию") }}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="/moderator/test/toall">
                @csrf
                  <input type="hidden" name="region_id" value="{{$region->id}}">
                  <input type="hidden" name="quiz_id" value="{{$quiz->id}}">
                  <input type="hidden" name="allow" value="1">
                  <button type="submit">{{ __("Открыть доступ всем") }}</button>
                </form>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __("Школа") }}</th>
                    <th>{{ __("Всего респондентов") }}</th>
                    <th>{{ __("Открыт доступ") }}</th>

                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($schools as $school) 
                  <tr>
                    <td>{{$school['school']->name}}</td>
                    <td>
                        {{$school['respondents_count']}}
                    </td>
                    <td class="d-flex justify-content-center">
                        <div class="custom-control custom-checkbox ">
                          <input class="custom-control-input" type="checkbox" id="sch{{$school['school']->id}}" value="{{$school['school']->id}}" @if ( $school['acl'] <> 0) checked @endif>
                          <label for="sch{{$school['school']->id}}" class="custom-control-label">{{ __("Доступ") }}</label>
                            <div class="spinner-border" role="status" id="spin{{$school['school']->id}}" style="display: none">
                                <span class="sr-only">Loading...</span>
                            </div>  

                                                  
                        </div>
                                        
                    </td>

                  </tr>
                  @endforeach		
                  </tbody>
                  <tfoot>
                  <tr>
                  <th>{{ __("Район") }}</th>
                    <th>{{ __("Всего пройдено") }}</th>
                    <th>{{ __("Средний бал") }}</th>

                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
<input type="hidden" id="quiz_inp" value="{{$quiz->id}}">
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

<!-- Moderator -->
<script src="/adm/dist/js/moderator-quiz.js"></script>

<!-- AdminLTE for demo purposes 
<script src="/adm/dist/js/demo.js"></script>-->

<script src="/adm/plugins/datatables/jquery.dataTables.js"></script>
<script src="/adm/plugins/datatables/jquery.dataTables.min.js"></script>
</body>
</html>
