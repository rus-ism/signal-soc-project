<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::middleware('auth')->group(function () {


    Route::get('/quiz-description/{id}', 'App\Http\Controllers\QuizController@description');
    Route::post('/testing/start', 'App\Http\Controllers\QuizprocessingController@start');
    Route::get('/testing', 'App\Http\Controllers\QuizprocessingController@noaction');
    Route::post('/testing/next', 'App\Http\Controllers\QuizprocessingController@next');
    Route::post('/testing/back', 'App\Http\Controllers\QuizprocessingController@back');
    Route::post('/testing/finish', 'App\Http\Controllers\QuizprocessingController@finish');


    //403
    Route::get('admin/403', 'App\Http\Controllers\AdminController@redirect403');

   // Route::get('admin/', 'App\Http\Controllers\AdminController@index');
    Route::get('admin/', 'App\Http\Controllers\Admin_resultController@index')->middleware('role:1');
    Route::get('admin/kz', 'App\Http\Controllers\Admin_resultController@index_kz')->middleware('role:1');
    Route::get('admin/tests', 'App\Http\Controllers\Admin_testController@index')->middleware('role:1');
    Route::get('admin/test/edit/{quiz_id}', 'App\Http\Controllers\Admin_testController@edit')->middleware('role:1');

    Route::get('admin/test/edit/table/{quiz_id}', 'App\Http\Controllers\Admin_testController@get_edit_table')->middleware('role:1');
    Route::get('admin/test/edit/interpret/{quiz_id}', 'App\Http\Controllers\Admin_testController@get_interpret_table')->middleware('role:1');
    Route::get('admin/test/quiz-table', 'App\Http\Controllers\Admin_testController@get_quiz_table')->middleware('role:1');

    Route::post('admin/test/question/add', 'App\Http\Controllers\Admin_testController@question_add')->middleware('role:1');
    Route::post('admin/test/question/update', 'App\Http\Controllers\Admin_testController@question_update')->middleware('role:1');
    Route::post('admin/test/question/delete', 'App\Http\Controllers\Admin_testController@question_delete')->middleware('role:1');
    Route::post('admin/test/answer/add', 'App\Http\Controllers\Admin_testController@answer_add')->middleware('role:1');
    Route::post('admin/test/answer/update', 'App\Http\Controllers\Admin_testController@answer_update')->middleware('role:1');
    Route::post('admin/test/answer/delete', 'App\Http\Controllers\Admin_testController@answer_delete')->middleware('role:1');

    Route::post('admin/test/quizacl/change', 'App\Http\Controllers\Admin_testController@quizacl_change')->middleware('role:1');

    Route::post('admin/test/interpret/add', 'App\Http\Controllers\Admin_testController@interpret_add')->middleware('role:1');
    Route::post('admin/test/interpret/delete', 'App\Http\Controllers\Admin_testController@interpret_delete')->middleware('role:1');

    Route::post('admin/test/quiz/add', 'App\Http\Controllers\Admin_testController@quiz_add')->middleware('role:1');

    Route::get('admin/results', 'App\Http\Controllers\Admin_resultController@index')->middleware('role:1');
    Route::get('admin/results/{quiz_id}', 'App\Http\Controllers\Admin_resultController@results_detail')->middleware('role:1');
    Route::get('admin/results/region/{region_id}/{quiz_id}/', 'App\Http\Controllers\Admin_resultController@results_region_detail')->middleware('role:1');
    Route::get('admin/results/school/{school_id}/{quiz_id}/', 'App\Http\Controllers\Admin_resultController@results_school_detail')->middleware('role:1');
    Route::get('admin/result/grade/{grade}/{quiz_id}/{school_id}', 'App\Http\Controllers\Admin_resultController@results_grade_detail')->middleware('role:1');
    Route::get('admin/result/{result_id}/{quiz_id}/', 'App\Http\Controllers\Admin_resultController@results_answers')->middleware('role:1');


    Route::get('admin/moderators', 'App\Http\Controllers\AdminController@show_moderators')->middleware('role:1');
    Route::post('admin/moderators/accept', 'App\Http\Controllers\AdminController@accept_moderator')->middleware('role:1');

    /* Respondents */
    Route::get('admin/respondents', 'App\Http\Controllers\Admin_resultController@respondents')->middleware('role:1');
    
    Route::get('admin/import/test', 'App\Http\Controllers\AdminController@testimportvew')->middleware('role:1');
    Route::post('admin/import/test/file', 'App\Http\Controllers\AdminController@testimport')->middleware('role:1');  

    /* maintenance */
    Route::get('admin/maintenance/recalculate/{quiz_id}', 'App\Http\Controllers\MaintenanceController@recalculateResults')->middleware('role:1');

    Route::get('admin/service', 'App\Http\Controllers\ServiceController@show_page')->middleware('role:1');
    Route::get('admin/service/delete_dublicates/{quiz_id}', 'App\Http\Controllers\ServiceController@delete_dublicates')->middleware('role:1');



    
/* Moderator */
    Route::get('moderator/', 'App\Http\Controllers\ModeratorController@dash')->middleware('role:2');
    Route::get('moderator/kz', 'App\Http\Controllers\ModeratorController@dash_kz')->middleware('role:2');
    Route::get('moderator/tests', 'App\Http\Controllers\ModeratorController@show_quiz')->middleware('role:2');
    Route::get('moderator/test/{id}', 'App\Http\Controllers\ModeratorController@quiz')->middleware('role:2');

    Route::get('moderator/schools', 'App\Http\Controllers\ModeratorController@show_schools')->middleware('role:2');
    Route::get('moderator/school/{school_id}', 'App\Http\Controllers\ModeratorController@school')->middleware('role:2');
    Route::post('moderator/school/rename', 'App\Http\Controllers\ModeratorController@rename_school')->middleware('role:2');

    Route::get('moderator/tutors', 'App\Http\Controllers\ModeratorController@tutors')->middleware('role:2');

    Route::post('/moderator/tutors/add', 'App\Http\Controllers\ModeratorController@add_tutor_form')->middleware('role:2');
    Route::post('/moderator/tutors/accept', 'App\Http\Controllers\ModeratorController@add_tutor_accept')->middleware('role:2');
    Route::post('/moderator/tutors/delete', 'App\Http\Controllers\ModeratorController@delete_tutor')->middleware('role:2');
    Route::post('/moderator/tutors/change_scool', 'App\Http\Controllers\ModeratorController@change_school')->middleware('role:2');

    Route::get('moderator/testsacl/{quiz_id}', 'App\Http\Controllers\ModeratorController@test_acl')->middleware('role:2');

    Route::post('moderator/test', 'App\Http\Controllers\ModeratorController@quiz_change_acl')->middleware('role:2');
    Route::post('moderator/test/toall', 'App\Http\Controllers\ModeratorController@quiz_change_acl_toall')->middleware('role:2');

    Route::post('moderator/tutors/change-count', 'App\Http\Controllers\ModeratorController@change_stud_count');
    Route::get('moderator/tutors/change-count', 'App\Http\Controllers\ModeratorController@change_stud_count');

    Route::get('moderator/results', 'App\Http\Controllers\Moderator_resultController@index')->middleware('role:2');
    Route::get('moderator/results/quiz/{quiz_id}', 'App\Http\Controllers\Moderator_resultController@results_region_detail')->middleware('role:2');
    Route::get('moderator/results/school/quiz/{school_id}/{quiz_id}', 'App\Http\Controllers\Moderator_resultController@results_school_detail')->middleware('role:2');
    Route::get('moderator/results/school/grade/quiz/{grade}/{quiz_id}/{school_id}', 'App\Http\Controllers\Moderator_resultController@results_grade_detail')->middleware('role:2');
    Route::get('moderator/results/answers/{result_id}/{quiz_id}/', 'App\Http\Controllers\Moderator_resultController@results_answers')->middleware('role:2');

    Route::get('tutor/', 'App\Http\Controllers\TutorController@quizes')->middleware('role:3');
    Route::get('tutor/schoolar', 'App\Http\Controllers\TutorController@schoolars')->middleware('role:3');

    Route::post('/tutor/schoolar/check_count', 'App\Http\Controllers\TutorController@schoolar_check_count')->middleware('role:3');
    Route::post('/tutor/schoolar/add', 'App\Http\Controllers\TutorController@schoolar_add')->middleware('role:3');
    Route::post('/tutor/schoolar/link', 'App\Http\Controllers\TutorController@schoolar_link')->middleware('role:3');
    Route::post('/tutor/schoolar/edit', 'App\Http\Controllers\TutorController@schoolar_edit')->middleware('role:3');
    Route::post('/tutor/schoolar/delete', 'App\Http\Controllers\TutorController@schoolar_delete')->middleware('role:3');
    Route::post('/tutor/schoolar/unlink', 'App\Http\Controllers\TutorController@schoolar_unlink')->middleware('role:3');

    Route::get('/tutor/result-school/{quiz_id}', 'App\Http\Controllers\TutorController@results_school_detail')->middleware('role:3');

    Route::get('/tutor/result/grade/{grade}/{quiz_id}', 'App\Http\Controllers\TutorController@results_grade_detail')->middleware('role:3');

    Route::get('/tutor/tests', 'App\Http\Controllers\TutorController@quizes')->middleware('role:3');

    Route::get('tutor/result/{result_id}/{quiz_id}/', 'App\Http\Controllers\TutorController@results_answers')->middleware('role:3');
    
});

Route::get('lang/{locale}', 'App\Http\Controllers\LocalizationController@index');

Route::get('/tpl', function () {
    return view('tpl2');
});


Route::get('/home', 'App\Http\Controllers\HomeController@index');       
Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('/kz', 'App\Http\Controllers\HomeController@index_kz');

Route::get('import/region/file', 'App\Http\Controllers\ImportController@importview');
Route::post('import/region', 'App\Http\Controllers\ImportController@import');

Route::get('admin/import/test', 'App\Http\Controllers\AdminController@testimportvew');
Route::post('admin/import/test/file', 'App\Http\Controllers\AdminController@testimport'); 

Route::get('import/school/file', 'App\Http\Controllers\ImportController@schoolview');
Route::post('import/school', 'App\Http\Controllers\ImportController@school');

Route::post('school/byregion', 'App\Http\Controllers\SchoolController@get_by_region');

Route::post('quizzes', 'App\Http\Controllers\HomeController@showQuizzes');
Route::post('public-test', 'App\Http\Controllers\QuizprocessingController@publictest');
Route::post('public-test/simplefinish', 'App\Http\Controllers\QuizprocessingController@simplefinish');

Route::get('mainten/rescalc/{user_id}/{result_id}', 'App\Http\Controllers\MaintenanceController@testresultcalc');

Route::get('debug/tutor/result/{result_id}/{quiz_id}/', 'App\Http\Controllers\TutorController@results_answers_debug');



Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
 
    return "Cleared!";
 
 });

//Route::get('mainten/fillkey', 'App\Http\Controllers\MaintenanceController@fill_keys');


/*
Route::get('/', function () {
    return view('index');
});


Route::get('/', function () {
    return view('index');
})->middleware(['auth']);
*/
