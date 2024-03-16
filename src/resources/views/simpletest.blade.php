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

	<link href="/css/app.css" rel="stylesheet">
	<link href="/css/custom.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>


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
							</div>
                            <form id="ans_form22" method="post" action="/public-test/simplefinish">
                            @csrf
                            @foreach ($questions as $question)
                                <div class="row justify-content-center">
                                    <div class="col-6">
                                        <div class="card">


                                                <div class="card-body">
                                                <div id="question-text" class="">
                                                    <p>
                                                        {{$question->text}}
                                                    </p>
                                                </div>													
                                                    <div id="answers-dv">
                                                            @foreach ($question->answer as $answer)
                                                            <label class="form-check">
                                                                <input class="form-check-input" type="radio" value="{{$answer->id}}" name="ans[{{$question->id}}]">
                                                                <span class="form-check-label">
                                                                    {{$answer->text}}
                                                                </span>
                                                            </label>													
                                                            @endforeach	
                                                            <input style="display:none" checked="checked" class="form-check-input" type="radio" value="0" name="ans[{{$question->id}}]">
                                                            <input id="respondent" type="hidden" name="respondent" value="{{$respondent}}" >
                                                            <input id="quiz_id" type="hidden" name="quiz_id" value="{{$quiz_id}}">
                                                            <input id="quizprocessing_id" type="hidden" name="quizprocessing_id" value="">
															<input id="started_quiz_id" type="hidden" name="started_quiz_id" value="{{$started_quiz_id}}">



                                                    </div>
                                                </div>	
                                                                                                                            

                                        </div>
                                    </div>
                                </div>
                            @endforeach	
                            <button type="submit" id="sub-btn" class="btn btn-primary">Завершить</button>
                            </form>


				</div>
			</main>


		</div>
	</div>

	<script src="/js/app.js"></script>
	<script src="/js/quiz-processing.js"></script>

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