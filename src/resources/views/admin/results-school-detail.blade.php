<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{$quiz->quiz_name}}, {{$school->name}}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/adm/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/adm/dist/css/adminlte.min.css">

    <!-- DataTables -->
  <link rel="stylesheet" href="/adm/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/adm/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/adm/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
            <h1>{{ __("Результаты анкетирования") }}: <span class="text-info">{{$quiz->quiz_name}}</span>, {{ __("в организации") }}: <span class="text-info">{{$school->name}}</span></h1>
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
                        <div class="row">
                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">{{ __("Всего пройдено") }}</span>
                                <span class="info-box-number text-center text-muted mb-0">{{$school_respondents_count}}</span>
                                </div>
                            </div>
                            </div>
                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">{{ __("Средний бал") }}</span>
                                <span class="info-box-number text-center text-muted mb-0">{{$school_bal_avg}}</span>
                                </div>
                            </div>
                            </div>

                        </div>
                        <div class="row">
                          <h5 class="mt-4 mb-2">
                          {{ __("Интерпретация результатов") }}:      
                          </h5>                              
                            <div class="col-12">
                            @foreach ($interprets as $interpret)
                              <div class="callout callout-info">
                              <h5>
                              {{ __("От") }}  
                                {{$interpret->from}}
                                  @if ($interpret->to > 500)
                                  {{ __("и более") }}
                                  @else
                                  {{ __("до") }} {{$interpret->to}}
                                  @endif  
                                 баллов:
                                </h5>
                                <p>{{$interpret->text}}</p>
                              </div>
                              @endforeach
                            </div>
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
                <h3 class="card-title">{{ __("Анкетирование в") }} {{$school->name}}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __("класс") }}</th>
                    <th>{{ __("количество") }}</th>
                    <th>{{ __("Контингент") }}</th>
                    @if($interprets->count() > 0)
                      @foreach ($interprets as $interpret)
                      <th>
                      {{ __("Набрали") }} 
                        {{$interpret->from}}
                              @if ($interpret->to > 500)
                              {{ __("и более") }}
                              @else
                                  -{{$interpret->to}}
                              @endif
                              {{ __("баллов, человек") }}
                       </th> 
                      @endforeach
                    @endif   
                    <th></th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($grades as $grade) 
                      
                      <tr>
                        <td>{{$grade['grade']}}</td>
                        <td>{{$grade['class_respondents_count']}}</td> 
                        <td>{{$grade['contingent']}}</td>                      
                        @if($interprets->count() > 0) 
                          @if (isset($grade['ranges']))                                                 
                            @foreach($grade['ranges'] as $range)
                            
                              <td>{{$range['count']}}</td>
                            @endforeach
                          @else <!-- Ranges not avialable -->
                              <td>0</td><td>0</td><td>0</td>
                          @endif
                        @endif                        

                        <td><a href="/admin/result/grade/{{$grade['grade']}}/{{$quiz->id}}/{{$school->id}}">{{ __("Подробно") }}</a> </td>
                      </tr>
                    
                  @endforeach		
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>{{ __("Итого") }}: </th>
                    <th>{{$school_respondents_count}}</th>
                    <th>{{$school_bal_avg}}</th>
                    @if($interprets->count() > 0)
                      @foreach($by_range_all as $by_range)
                        <th>
                        {{ __("от") }} {{$by_range['range']['from']}} 
                            @if ($by_range['range']['to'] > 500)
                            {{ __("и более") }}:
                            @else
                            {{ __("до") }} {{$by_range['range']['to']}}:
                            @endif
                             {{$by_range['count']}}
                        </th>
                      @endforeach
                    @endif
                    <th></th>
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

<script src="/adm/plugins/datatables/jquery.dataTables.js"></script>
<script src="/adm/plugins/datatables/jquery.dataTables.min.js"></script>

<script src="/adm/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/adm/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/adm/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/adm/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/adm/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/adm/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/adm/plugins/jszip/jszip.min.js"></script>
<script src="/adm/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/adm/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/adm/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/adm/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/adm/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
       "lengthChange": false, 
       "autoWidth": false,
       "ordering": true,
       "paging": false,
       "autoWidth": true,
       //"aaSorting": [],
      "columnDefs": [{
      "orderable": false,
      "targets": [6],
      }],
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
  });
</script>
</body>
</html>
