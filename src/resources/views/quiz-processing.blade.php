<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	
<!--<link rel="stylesheet" href="https://unpkg.com/@adminkit/core@latest/dist/css/app.css">-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>	

	<title>Тестирование</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/custom.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>

<!-- MODAL -->

<div id="id01" class="modal" tabindex="-100000000000">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
  <form class="modal-content" action="/testing/finish" method="post">
  	@csrf
	<input type="hidden" name="m_result" value="{{$result}}">
	<input type="hidden" name="m_result_id" value="{{$result->id}}">
	<input type="hidden" name="m_quizprocessing_id" value="{{$quizprocessing_id}}">
    <div class="mcontainer">
      <h1>Завершить тестирование?</h1>
      <p>После завершения возобновить этот тест будет не возможно</p>
    
      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Отмена</button>
        <button type="submin" onclick="document.getElementById('id01').style.display='none'" class="deletebtn">Завершить</button>
      </div>
    </div>
  </form>
</div>


<!-- END MODAL -->	
	<div class="wrapper">

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">


			<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									
								</div>
								<div class="list-group">
									
								</div>

							</div>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                				<i class="align-middle" data-feather="settings"></i>
             				 </a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                			 <span class="text-dark">{{$profile->fio}}</span>
              				</a>
                              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								Выйти
							</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST">
								@csrf
							</form>		                                                        
						</li>
					</ul>
				</div>
			</nav>			

			<main class="content">
				<div class="container-fluid p-0">

							<div class="row">
								<div class="card">
									<div class="card-body">
										<div id="question-bar" class="btn-group me-2 " role="group" aria-label="First group">
										@foreach ($question_lists as $questions)
											
											<button id="quest{{$questions->id}}" type="button" class="btn btn-secondary question-list">{{$questions->counter}}</button>
										@endforeach	
										</div>	
										<button class="btn btn-success" onclick="document.getElementById('id01').style.display='block'">Завершить</button>									
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-6">
									<div class="card">
											<div class="card-header">

												<h5 id="question-title" class="card-title mb-0">Вопрос: {{$current}}</h5>
											</div>
											<div id="question-text" class="card-body">
												<p>
													{{$question->text}}
												</p>
											</div>

											<div class="card-body">
												<div id="answers-dv">
													<form id="answers-form" method="post">
														@csrf
														@foreach ($answers as $answer)
														<label class="form-check">
															<input class="form-check-input" type="radio" value="{{$answer->id}}" name="answer">
															<span class="form-check-label">
																{{$answer->text}}
															</span>
														</label>													
														@endforeach	
														<input id="result" type="hidden" name="result_id" value="{{$result->id}}">
														<input id="question_list_id" type="hidden" name="question_list_id" value="{{$question_list->id}}">
														<input id="quizprocessing_id" type="hidden" name="quizprocessing_id" value="{{$quizprocessing_id}}">


													</form>
												</div>
											</div>	
									<div class="card-body text-center">
										<div class="mb-2">
											<button id="back-btn" class="btn btn-primary">Предыдущий</button>
											<button id="next-btn" class="btn btn-primary">Следующий</button>

										</div>
									</div> 																														

									</div>
								</div>

							</div>

				</div>
			</main>


		</div>
	</div>

	<script src="{{ asset('/js/app.js') }}"></script>
	<script src="{{ asset('/js/quiz-processing.js') }}"></script>

	<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<script type="text/javascript">
    window.onbeforeunload = function() {
        return "Dude, are you sure you want to leave? Think of the kittens!";
    }
</script>
</body>

</html>