<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{$quiz->quiz_name}}, {{$profile->fio}}</title>

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
  @include('moder.leftside')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __("Результаты теста") }}:</h1>
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
                          @if($scales == null)
                            <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">{{ __("Баллов") }}:</span>
                                <span class="info-box-number text-center text-muted mb-0">{{$result->scope}}</span>
                                </div>
                            </div>
                            </div>
                          @endif

                        </div>
                        @if ($interpret)
                        <div class="row">
                          <h5 class="mt-4 mb-2">
                          {{ __("Интерпретация результата") }}:      
                          </h5>                              
                            <div class="col-12">
                              <div class="callout {{$color_style}}">
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
                            </div>
                        </div>                        
                        @endif
                    </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <p class="text-muted">{{$quiz->quiz_description}}</p>

                    <br>
                </div>
                </div>



                @if($scales != null)
                            <div class="row">
                                <h5 class="mt-4 mb-2">
                                {{ __("Интерпретация результатов:") }}:           
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
                <h3 class="card-title">{{ __("Анкетирование") }} *************</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __("Вопрос") }}</th>
                    <th>{{ __("твет") }}</th>
                    <th>{{ __("Бал") }}</th>
                    <th>{{ __("Дата") }}</th>
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
                    <th>{{ __("Вопрос") }}</th>
                    <th>{{ __("Ответ") }}</th>
                    <th>{{ __("Бал") }}</th>
                    <th>{{ __("Дата") }}</th>
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
