<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
<!--<link rel="stylesheet" href="https://unpkg.com/@adminkit/core@latest/dist/css/app.css">-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

	<title>Доступные тесты</title>

	<link href="/css/app.css" rel="stylesheet">
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
						<a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								{{ __("Выйти") }}
				</a>
        <form id="logout-form" action="/logout" method="POST">
								@csrf
				</form>  						
						</li>
					</ul>
				</div>
			</nav>


			<main class="content">

				<div class="container-fluid p-0">

					<h1 class="h3 mb-3">{{ __("Доступные тесты") }}</h1>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0"></h5>
								</div>
								<div class="card-body">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th style="width:40%;">{{ __("Тест") }}</th>
											<th style="width:25%">{{ __("Колличество вопросов") }}</th>
											<th class="d-none d-md-table-cell" style="width:25%">{{ __("Активен до") }}:</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									@if (count($quizzes) > 0)
									@foreach ($quizzes as $quiz)
										<tr>
											<td>{{$quiz->quiz_name}}</td>
											<td>{{$quiz->question()->count()}}<td>
											<td class="d-none d-md-table-cell">-</td>
											<td class="table-action">
												<form method="post" action="/public-test">
												@csrf
												<input type="hidden" name="quiz_id" value="{{$quiz->id}}">
												<input type="hidden" name="respondent_id" value="{{$respondent_id}}">
												<button type="submit">{{ __("начать") }}</button>
												</form>
												<!--
												<a href="/public-test/{{$quiz->id}}/{{$respondent_id}}">начать <i class="align-middle" data-feather="play"></i></a>
												<a href="#"><i class="align-middle" data-feather="trash"></i></a> -->
											</td>
										</tr>
									@endforeach
									
									@else
										<tr>
											<td colspan="4" style="text-align:center">
												<span>{{ __("Нет доступных тестов") }}</span>
											</td>
										</tr>
									@endif
									</tbody>
								</table>								
								</div>
							</div>
						</div>
					</div>

				</div>
			</main>


			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="https://mcioko.edu.kz/" target="_blank"><strong>{{ __("Методический центр информатизации и оценки качества образования") }}</strong></a> &copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
								{{ __("поддержка") }}: (7142) 21-16-41
								</li>

							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="/js/app.js"></script>

</body>

</html>