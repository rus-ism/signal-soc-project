<!DOCTYPE html>
<html lang="en">
  <head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __("Анкетирование") }}</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

  </head>
  <body>

  <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="row header">
        <div class="col-md-3 ">
          <div class="image-wrapper header-left-img">
            <img src="/img/layout/header-left.png">
          </div>
				</div>
        <div class="col-md-6">
          <div class="header-title">
            <h1>{{ $quiz->quiz_name }}.</h1>
          </div>
				</div>
        <div class="col-md-3">
        <div class="image-wrapper header-left-img">
          <img src="/img/layout/header-right.png">
          </div>
				</div>
			</div>

			<div class="row all-height">
				<div class="col-md-3">
          <div class="image-wrapper side-top-img">

          </div>
          <div class="image-wrapper side-top-img">

          </div>          
				</div>
				<div class="col-md-6">
          <div class="content-wrapper">

            <form id="ans_form22" method="post" action="/public-test/simplefinish">
            @csrf
            @php($i=0)
            @foreach ($questions as $question) 

                @php($i++)
                <div class="question-wrapper">
                  <div class="question-text">
                    <p><span>{{$i}}. </span> {{$question->text}}</p>
                  </div>

                  <div class="answer-wrapper">
                  @foreach ($question->answer as $answer)
                    <div class="answer">
                      <input class="radiobtn" id="q1{{$question->id}}a{{$answer->id}}" type="radio"  value="{{$answer->id}}" name="ans[{{$question->id}}]"/>
                      <label for="q1{{$question->id}}a{{$answer->id}}">{{$answer->text}}</label>
                    </div>
                  @endforeach
                  </div>
                </div>
              @endforeach
              <input style="display:none" checked="checked" class="form-check-input" type="radio" value="0" name="ans[{{$question->id}}]">
              <input id="respondent" type="hidden" name="respondent" value="{{$respondent}}" >
              <input id="quiz_id" type="hidden" name="quiz_id" value="{{$quiz_id}}">
              <input id="quizprocessing_id" type="hidden" name="quizprocessing_id" value="">
							<input id="started_quiz_id" type="hidden" name="started_quiz_id" value="{{$started_quiz_id}}">

                <div class="button-wrapper">
                  <button type="submit" id="submit" class="btn">{{ __("Завершить") }}</button>
                </div>
              </form>



          </div>
				</div>
				<div class="col-md-3">
        <div class="image-wrapper side-top-img">

          </div>
          <div class="image-wrapper side-top-img">

          </div> 
				</div>
			</div>
		</div>
	</div>
</div>

    <script src="/js/layout/jquery.min.js"></script>
    <script src="/js/layout/bootstrap.min.js"></script>
    <script src="/js/layout/scripts.js"></script>
  </body>
</html>