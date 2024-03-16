<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <title>{{ __("Психологи") }}</title>

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
            <h1>{{ __("Психологи") }}</h1>
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
                        
                          <form action="/moderator/tutors/accept" method="post">
                          @csrf
                            <input class="custom-control-input" style="width:50px" type="hidden" name="request_id" id="rq{{$request->id}}" value="{{$request->id}}">
                            <button type="submit" class="btn btn-block btn-primary btn-lg">{{ __("Подтвердить") }}</button>
                          </form>                                                
                        
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


      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"></h3>


        </div>
        <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>N</th>
                    <th>{{ __("Логин") }}</th>
                    <th>{{ __("ФИО") }}</th>
                    <th>{{ __("Школа") }}</th>         
                    <th>{{ __("Колиичество учащихся") }}</th>
                    <th>{{ __("Удалить психолога") }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @php $i = 0; @endphp
									@if (count($tutors) > 0)
									@foreach ($tutors as $tutor)    
                  
                  <?php #echo('<br>--------<br>'); var_dump($tutor); echo('<br>--------<br>') ?>
                 
                  
                    @if ($tutor['tutors'] > 0)
                    
                    @foreach ($tutor['tutors'] as $ttutor)                      
                    @php $i++; @endphp
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$ttutor->user()->first()->email}}</td>
                        <td>{{$ttutor->fio}}</td>
                        <td><a href="">{{$tutor['school']->name}}</a></td>
                        <td>
                        <!-- Button trigger modal -->
                          <button id="stud_count_btn{{$tutor['school']->id}}" type="button" class="btn btn-primary studcnt" data-toggle="modal" data-target="#exampleModal" value="{{$tutor['school']->id}}">
                          {{$tutor['schooler_count_str']}}
                          </button>
                        </td>
                        <td>
                            <input type="hidden" name="tutor_id" value="{{$ttutor->user()->first()->id}}">
                            <i class="fas fa-trash del_tutor" style="font-size:15px; cursor:pointer"></i>                            
                            <i class="fas fa-edit edit_tutor" style="font-size:15px; cursor:pointer"></i>
                                                
                        </td>

                      </tr>
                    @endforeach  
                    @endif 
									@endforeach
									
									@else
                  <tr>
                    <td></td>
                    <td><a href="/"></a></td>
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
                    <th>{{ __("Итого") }}:</th>
                    <th>{{ __("психологов") }}: {{$tutors_count}}</th>           
                    <th>{{ __("всего школ") }}: {{$schools_count}}</th>            
                    <th>{{ __("учащихся") }}: {{$schoollar_count}}</th>
                    <th></th>
                  </tr>
                  </tfoot>
                </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">

        </div>
        <!-- /.card-footer-->
      <form id="tutor_add_form" action="/moderator/tutors/add" method="post">
      @csrf
        <input type="hidden" name="action" value="show_form">
        <button type="submit" class="btn btn-block btn-primary btn-lg">{{ __("Добавить психолога") }}</button>
      </form>
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
        <h5 class="modal-title" id="exampleModalLabel">{{ __("Введите новые значения количества учащихся") }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form  id="stud_count_form">
        <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("класс") }} 5:</label>
                    <input type="number" class="form-control stud_count_input" id="5grade" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("класс") }} 6:</label>
                    <input type="number" class="form-control stud_count_input" id="6grade" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("класс") }} 7:</label>
                    <input type="number" class="form-control stud_count_input" id="7grade" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("класс") }} 8:</label>
                    <input type="number" class="form-control stud_count_input" id="8grade" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("класс") }} 9:</label>
                    <input type="number" class="form-control stud_count_input" id="9grade" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("класс") }} 10:</label>
                    <input type="number" class="form-control stud_count_input" id="10grade" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ __("класс") }} 11:</label>
                    <input type="number" class="form-control stud_count_input" id="11grade" placeholder="">
                  </div>     
                  <input type="hidden" value="" id="stud_count_school_id">

                </div>
                <!-- /.card-body -->

                <div class="card-footer">

                </div>          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="stud_count_close" class="btn btn-secondary" data-dismiss="modal">{{ __("Отмена") }}</button>
        <button type="button" id="stud_count_save" class="btn btn-primary">{{ __("Сохранить") }}</button>
      </div>
    </div>
  </div>
</div>

<!-- END Modal: Grade input -->


<!-- Modal: Edit Tutor -->

<div class="modal fade .modal-fullscreen" id="edit_tutor_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __("Введите новые значения количества учащихся") }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form  id="stud_count_form">
        <div class="card-body">
                  <div class="form-group">
                    <select id="selSchool" class="form-select form-select-lg mb-3" aria-label=".form-select-sm example" name="school" required>
                    @foreach ($schools as $school)
                      <option value="{{$school->id}}">{{$school->name}}</option>
                    @endforeach
                      
                    </select>
                  </div>
  
                  <input type="hidden" value="" id="edit_tutor_modal_user_id">

                </div>
                <!-- /.card-body -->

                <div class="card-footer">

                </div>          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="edit_tutor_modal_close" class="btn btn-secondary" data-dismiss="modal">{{ __("Отмена") }}</button>
        <button type="button" id="edit_tutor_modal_save" class="btn btn-primary">{{ __("Сохранить") }}</button>
      </div>
    </div>
  </div>
</div>

<!-- END Modal: Edit Tutor -->




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
