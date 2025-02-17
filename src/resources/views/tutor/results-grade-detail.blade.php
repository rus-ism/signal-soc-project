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
                Тесты

              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/tutor/schoolar" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Ученики

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
            <h2>Психолог: {{$school->name}}</h2>
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
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Результаты теста:</h1>
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
                                <span class="info-box-text text-center text-muted">Всего пройдено</span>
                                <span class="info-box-number text-center text-muted mb-0">{{$grade_count}}</span>
                                </div>
                            </div>
                            </div>


                        </div>
                        <div class="row">
                          <h5 class="mt-4 mb-2">
                            Интерпретация результатов:      
                          </h5>                              
                            <div class="col-12">
                            @foreach ($interprets as $interpret)
                              <div class="callout callout-info">
                                <h5>
                                  От  
                                {{$interpret->from}}
                                  @if ($interpret->to > 500)
                                      и более
                                  @else
                                    до {{$interpret->to}}
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
                <h3 class="card-title">Анкетирование в {{$school->name}}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ФИО</th>
                    <th>Класс</th>
                    @if($quiz->type_id != 2)
                      <th>Балл</th>
                    @endif
                    <th>Дата</th>
                    <th>...</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($results as $result) 
                    @foreach ($result['result'] as $rs)                     
                    @php 
                    $backcolor = '';
                    if (isset($rs['assessment']))
                      {
                        if ($rs['assessment'] == 1) {$backcolor = '#DCFECF';} ;
                        if ($rs['assessment'] == 2) {$backcolor = '#FEFCCF';} ;
                        if ($rs['assessment'] == 3) {$backcolor = '#FFCCCC';} ;
                      }
                    @endphp
                      <tr style="background-color: {{$backcolor}};">
                        <td>{{$result['profile']->fio}}</td> 
                        <!--<td>**********</td>-->
                        <td>{{$result['respondent']->grade}}-{{$result['respondent']->litera}}</td>
                        @if($quiz->type_id != 2)
                          <td>{{$rs['resp_res']->scope}}</td>
                        @endif
                        <td>{{$rs['resp_res']->updated_at}}</td>

                        <td><a href="/tutor/result/{{$rs['resp_res']->id}}/{{$quiz->id}}/">Подробно</a> </td>
                      </tr>
                    @endforeach	
                  @endforeach	
                  </tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
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
      "targets": [4],
      }],
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
  });
</script>
</body>
</html>
