<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Результат</title>

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
                                <span class="info-box-number text-center text-muted mb-0">{{$quiz->respondent_result()->get()->count()}}</span>
                                </div>
                            </div>
                            </div>


                        </div>

                    </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                   <!-- <p class="text-muted">{{$quiz->quiz_description}}</p> -->
                    <br>
                </div>
                </div>

                @if($scales != 0)
               <div class="row">
                  <h5 class="mt-4 mb-2">
                    Интерпретация результатов:      
                  </h5>                              
                  
                  <div class="col-12">
                            @foreach ($scales as $scale)
                              @php
                                if ($scale['scope'] >= $scale['max']) {
                                  $clr = 'callout-danger';
                                } elseif($scale['scope'] != 0) {
                                  $clr = 'callout-warning';
                                }  else {
                                  $clr = 'callout-info';
                                }                          
                              @endphp
                              <div class="callout {{$clr}}">
                                <h5>
                                  {{$scale['title']}}
                                </h5>
                                <p><strong>
                                  {{$scale['scope']}} {{__('из')}} {{$scale['max']}}
                                </strong></p>
                                <p>{{$scale['description']}}</p>
                              </div>
                              @endforeach
                   </div>
                </div>
                @endif

            </div>
            <!-- /.card-body -->
            <div class="card-footer">

            </div>
            <!-- /.card-footer-->
        </div>
      <!-- /.card -->


      <div class="card">
              <div class="card-header">
                <h3 class="card-title">Анкетирование ***************<!-- {{$profile->fio}} No need FIO--></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Вопрос</th>
                    <th>Ответ</th>
                    <th>Бал</th>
                    <th>Дата</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($qas as $qa)                       
                      <tr>
                        <td>{{$qa['question']}}</td>
                        <td>{{$qa['answer']}}</td>
                        <td>{{$qa['scope']}}</td>
                        <td>{{$qa['date']}}</td>
                      </tr>                    
                  @endforeach		
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Вопрос</th>
                    <th>Ответ</th>
                    <th>Бал</th>
                    <th>Дата</th>
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
</body>
</html>
