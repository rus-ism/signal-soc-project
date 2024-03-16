<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
<!--<link rel="stylesheet" href="https://unpkg.com/@adminkit/core@latest/dist/css/app.css">-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

	<title>Тестирование</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/custom.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>

<!-- MODAL -->

<div id="id01" class="modal" tabindex="-100000000000">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
  <form class="modal-content" action="/action_page.php">
    <div class="mcontainer">
      <h1>Завершить тестирование?</h1>
      <p>После завершения возобновить этот тест будет не возможно</p>
    
      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Отмена</button>
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="deletebtn">Завершить</button>
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
                			 <span class="text-dark">Василий Пупкин</span>
              				</a>
						</li>
					</ul>
				</div>
			</nav>			

			<main class="content">
				<div class="container-fluid p-0">

							<div class="row">
								<div class="card">
									<div class="card-body">
										<div class="btn-group me-2 " role="group" aria-label="First group">
											<button type="button" class="btn btn-primary">1</button>
											<button type="button" class="btn btn-primary active">2</button>
											<button type="button" class="btn btn-primary">3</button>
											<button type="button" class="btn btn-primary">4</button>
											<button type="button" class="btn btn-primary">5</button>
											<button type="button" class="btn btn-primary">6</button>
											<button type="button" class="btn btn-primary">7</button>
											<button type="button" class="btn btn-primary">8</button>
											<button type="button" class="btn btn-primary">9</button>
											<button type="button" class="btn btn-primary">10</button>
										</div>	
										<button class="btn btn-success" onclick="document.getElementById('id01').style.display='block'">Завершить</button>									
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-6">
									<div class="card">
											<div class="card-header">

												<h5 class="card-title mb-0">Вопрос: 1</h5>
											</div>
											<div class="card-body">
												<p>
													Не чувствую в себе уверенности
												</p>
											</div>

											<div class="card-body">
												<div>
													<label class="form-check">
										            <input class="form-check-input" type="radio" value="option1" name="answer" checked>
										            <span class="form-check-label">
										              Часто встречается
										            </span>
										          </label>
																				<label class="form-check">
										            <input class="form-check-input" type="radio" value="option2" name="answer">
										            <span class="form-check-label">
										              Бывает, но изредка
										            </span>
										          </label>
												  <label class="form-check">
										            <input class="form-check-input" type="radio" value="option3" name="answer">
										            <span class="form-check-label">
										              Совсем не бывает
										            </span>
										          </label>
												</div>
											</div>	
									<div class="card-body text-center">
										<div class="mb-2">
											<button class="btn btn-primary">Предыдущий</button>
											<button class="btn btn-primary">Следующий</button>

										</div>
									</div> 																														

									</div>
								</div>

							</div>

				</div>
			</main>


		</div>
	</div>

	<script src="js/app.js"></script>


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

</body>

</html>