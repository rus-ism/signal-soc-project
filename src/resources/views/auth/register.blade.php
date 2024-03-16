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

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-up.html" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

	<title>Регистрация</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2">Регистрация</h1>
							<p class="lead">
								Регистрация в системе
							</p>
						</div>

						<div class="card">
							<div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
								<div class="m-sm-4">
									<form method="post" action="/register">
                                    @csrf
									<div class="mb-3">
										<div class="form-group">
												<label class="form-label">Выберите роль</label>
												<div class="custom-control custom-radio">
												<input class="custom-control-input" type="radio" id="role1" name="role" value="2">
												<label for="role1" class="custom-control-label">Модератор (отдел образования)</label>
												</div>
												<div class="custom-control custom-radio">
												<input class="custom-control-input" type="radio" id="role2" name="role" value="3">
												<label for="role2" class="custom-control-label">Психолог (школа)</label>
												</div>
											</div>
									</div>

				

										<div class="mb-3">
											<label class="form-label">Имя</label>
											<input class="form-control form-control-lg" type="text" name="name" placeholder="Введите ФИО" />
										</div>
										<div class="mb-3">
											<label class="form-label">ИИН</label>
											<input class="form-control form-control-lg" type="text" name="email" placeholder="Введите ИИН" />
										</div>
										<div class="mb-3">
											<label class="form-label">Город / район</label>
											<select id="selRegion" class="form-select form-select-lg" aria-label=".form-select-sm example" name="region">
													<option selected value="">...</option>
												@foreach ($regions as $region)
												    <option value="{{$region->id}}">{{$region->name}}</option>
                                                @endforeach
											</select>
										</div>
										<div class="mb-3">
											<label class="form-label">Выберите школу *</label>
											<select id="selSchool" class="form-select form-select-lg" aria-label=".form-select-sm example" name="school_id" disabled required>
												    
											</select>
										</div>										
										<div class="mb-3">
											<label class="form-label">Пароль</label>
											<input class="form-control form-control-lg" type="password" name="password" placeholder="Придумайте пароль" />
										</div>
										<div class="mb-3">
											<label class="form-label">Пароль еще раз</label>
											<input class="form-control form-control-lg" type="password" name="password_confirmation" placeholder="Повторите пароль" />
										</div>                                        
										<div class="text-center mt-3">
											<!--<a href="index.html" class="btn btn-lg btn-primary">Sign up</a> -->
											 <button type="submit" class="btn btn-lg btn-primary">Регистрация</button>
										</div>
									</form>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</main>

	<script src="js/app.js"></script>
	<script src="js/respondent-form.js"></script>

</body>

</html>