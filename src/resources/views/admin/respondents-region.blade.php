<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Респонденты</title>

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
    @include('admin.sidebar')  
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
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
                <h1 class="card-title"></h1>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">{{ __("Всего пройдено") }}</span>
                                <span class="info-box-number text-center text-muted mb-0">0</span>
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


      <div class="card">
              <div class="card-header">
                <h3 class="card-title">Контингент в разрезе районов</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __("Район") }}</th>
                    <th>{{ __("Контингент") }}</th>
     
                    <th>{{ __("Респондентов") }}</th>
                    <th>{{ __("Пройдено тестов") }}</th>
                  </tr>
                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>
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
