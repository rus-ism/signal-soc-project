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

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-up.html" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

	<title>Регистрация</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="/css/custom.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/css/adminlte.min.css">

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	
	
</head>

<body>
<div class="row">
			<div class="lang-top">
				<div class="lang-swither">
					<ul class="lang-list">
						<li class="{{ \Session::get('locale') == 'ru' ? 'lang-current' : '' }}"><a href="/lang/ru">Русский</a></li>
						<li class="{{ \Session::get('locale') == 'kz' ? 'lang-current' : '' }}"><a href="/lang/kz">Казахский</a></li>
					</ul>
				</div>
			</div>
	</div>	
	<main class="d-flex w-100">

		<div class="container d-flex flex-column">


			<div class="row vh-100">


				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">


						<div class="text-center mt-4">
							<h1 class="h2">@lang("Анкетирование")</h1>
							<p class="lead">
								{{ __("Для продолжения Вам необходимо указать некоторые данные") }}
							</p>
						</div>

						<div class="card">
							<div class="card-body">

								<div class="m-sm-4">
									<form method="post" action="/quizzes">
                                    @csrf
										<div class="mb-3">
											<label class="form-label">{{ __("Выберите город / район") }} *</label>
											<select id="selRegion" class="form-select form-select-lg" aria-label=".form-select-sm example" name="region" required>
											<option selected value="0">...</option>
                                                @foreach ($regions as $region)
												    <option  value="{{$region->id}}">{{$region->name}}</option>
                                                @endforeach

											</select>
										</div>
										<div class="mb-3">
											<label class="form-label">{{ __("Выберите школу") }} *</label>
											<select id="selSchool" class="form-select form-select-lg" aria-label=".form-select-sm example" name="school" disabled required>
												    
											</select>
										</div>																				
										<div class="mb-3">
											<label class="form-label">{{ __("Ввдите класс") }} *</label>
											<input class="form-control form-control-lg" type="text" name="grade" placeholder="{{ __("Класс") }}" required/>
										</div>			
										<div class="mb-3">
											<label class="form-label">{{ __("Литера") }}</label>
											<input class="form-control form-control-lg" type="text" name="litera" placeholder="{{ __("Литера") }}" />
										</div>
										<div class="mb-3">
											<label class="form-label">{{ __("Код доступа") }} *</label>
											<input class="form-control form-control-lg" type="text" name="ic" placeholder="{{ __("Код доступа") }}" required/>
										</div>																													
                                     
										<div class="text-center mt-3">
											<!--<a href="index.html" class="btn btn-lg btn-primary">Sign up</a> -->
											 <button type="submit" class="btn btn-lg btn-primary">{{ __("Пройти анкетирование") }}</button>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name=language]').change(function() {
                var lang = $(this).val();
				console.log(lang);
                window.location.href = "/lang/"+lang;
            });

			$(function(){
    $('.selectpicker').selectpicker();
});
 
			
        });
    </script>
</body>

</html>