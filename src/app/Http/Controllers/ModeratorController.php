<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Region;
use App\Models\School;
use App\Models\Profile;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Quizacl;
use App\Models\Respondent;
use App\Models\Quiz_school_acl;
use App\Models\Started_quiz;
use App\Models\User_request;
use App\Models\Scholler_count;


use Illuminate\Support\Facades\Hash;


class ModeratorController extends Controller
{

    public function dash(Request $request)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();  
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            abort(403, 'Доступ запрещен');
        }   
        
        
        $region = $profile->region()->first();   

        $all_respondents = Respondent::where('region_id', $region->id)->get()->count();

        // By school
        $schools = $region->school()->get();
        $started_school_count =0;
        $started_count = 0;
        $started = [];        
        $schools_array = [];
        $i = 0;
        foreach ($schools as $school)
        {
            $started_quizzes_by_school = $school->started_quiz()->get();
            if ($started_quizzes_by_school->count() != 0)
            {
                $started_school_count++;
            }
            $started_count += $started_quizzes_by_school->count();
            foreach ($started_quizzes_by_school as $started_quiz)
            {
                $started[] = $started_quiz;
            }
            //dd($school->scholler_count()->get());
            $schools_array[$i]['school'] = $school;
            $schools_array[$i]['respondents_count'] = $school->respondent()->get()->count();
            $schools_array[$i]['moderator_count']   = $school->scholler_count()->get()->sum('count');
            $schools_array[$i]['tutor_count']       = $school->profile()->where('role_id', 0)->get()->count();
            if ($schools_array[$i]['moderator_count'] != 0 ) 
            {
                $schools_array[$i]['pircent']           = round($schools_array[$i]['tutor_count'] * 100 / $schools_array[$i]['moderator_count'],0);
            } else {
                $schools_array[$i]['pircent'] = 0;
            }
            if ($schools_array[$i]['pircent'] < 51)
            {
                $schools_array[$i]['box_class'] = 'bg-warning';
            }
            if (($schools_array[$i]['pircent'] > 50) AND ($schools_array[$i]['pircent'] < 71))
            {
                $schools_array[$i]['box_class'] = 'bg-info';
            }  
            if ($schools_array[$i]['pircent'] > 70)
            {
                $schools_array[$i]['box_class'] = 'bg-success';
            }                      
            $i++;
        }
        //dd($schools_array);

        $data = [
            'region'                => $region,
            'started'               => $started,
            'started_school_count'  => $started_school_count,
            'started_count'         => $started_count,
            'all_respondents'       => $all_respondents,
            'schools'                => $schools_array,
        ];            
        return view('moder.dash', $data);
    }


    #add tutor form and add
    public function add_tutor_form(Request $request)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first();  
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
            {
                return back()->withInput();
            }          
        $region = $profile->region()->first();         
        $action = $request->input('action');


       // dd($action);
        if ($action == 'show_form')
        {
            $schools = $region->school()->get();
            $data = [
                'region'  => $region,
                'schools' => $schools,
            ];    
            return view('moder.add-tutor', $data);
        }
        if ($action == 'add')
        {

            $name = $request->input('name');
            $email = $request->input('email');
            $password = $request->input('password');
            $school_id = $request->input('school_id');
            $school = School::find($school_id);

            $tutor_user = User::where('email', '=', $email)->first();
            //dd($tutor_user);
            if ($tutor_user) {
                $tutor_user->name      = $name;
                $tutor_user->password  = Hash::make($password);
                $tutor_user->save();
                $tutor_profile = $tutor_user->profile()->first();
                $tutor_profile->region_id  = $region->id;
                $tutor_profile->scool_name = $school->name;
                $tutor_profile->scool_id   =$school_id;
                $tutor_profile->save();
            } else {

                    $tutor_user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'role' => 3,
                        'password' => Hash::make($password),                
                    ]);
            
                    $profile = Profile::create([
                        'user_id'           =>      $user->id,
                        'role_id'           =>      3,
                        'region_id'         =>      $region->id,
                        'scool_name'        =>      $school->name,
                        'scool_id'          =>      $school_id,
                        'grade'             =>      null,
                        'fio'               =>      $name,            
                        ]);
                    }


            return redirect()->action([ModeratorController::class, 'tutors']);
        }        
    }

    #Tutors view
    public function tutors(Request $request)
    {
        $user = $this->auth();        
        $profile = $user->profile()->first(); 
        


        
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
            {
                //dd($profile->role_id);
                return view('error.403');
            }                    

        
        $region = $profile->region()->first(); 
        //dd($profile);

        $user_requests = User_request::where('role', 3)->where('region_id', $region->id)->get();
        //$users = User::where('role', 3)->get();

        $schools = $region->school()->get();  
        $schools_count = $schools->count();    
        $tutors = [];
        $all_schollars_count = Scholler_count::where('region_id', $region->id)->get()->sum('count');
        $i = 0;
        foreach ($schools as $school)
        {
            $tutors[$i]['school'] = $school;
            $schollar_counts = $school->scholler_count()->get()->sortBy(['grade','desc']);
            $schoollars = $school->scholler_count()->sum('count');
           //dd($schollar_counts->count());
           #schoolar count
           if ($schollar_counts->count() > 0) 
            {
                //All grades pass
                $schoollar_count_str = '';
                $schollar_count_array = [];
                foreach($schollar_counts as $cnt)
                {
                    //dd($cnt->count);
                    $schollar_count_array[]['grade'] = $cnt->grade;
                    $schollar_count_array[]['count'] = $cnt->count;
                    $schoollar_count_str .= ' '. $cnt->grade.'класс: '.$cnt->count . '; ';
                }
                               
            } else {
                $schoollars = 0;
                $schoollar_count_str = '';
            }

            $tutors[$i]['schooler_count'] = $schoollars;
            $tutors[$i]['schooler_count_str'] = $schoollar_count_str; 
      //dd($schoollars->grade5);
      //var_dump($schoollars);

           
            $school_tutors = $school->profile()->where('role_id', 3)->get();
            
            if ($school_tutors->count() > 0)
                {
                    $scool_tutor_array = [];
                    foreach ($school_tutors as $school_tutor)
                    {                    
                        $scool_tutor_array[] = $school_tutor;
                    }
                    
                } else {
                    $scool_tutor_array = 0;
                }
            $tutors[$i]['tutors'] = $scool_tutor_array;
            //dd($tutors);
            $i++;
        
        }
        
        

        $data = [
            'region'                => $region,
            'tutors'                => $tutors,
            'user_requests'         => $user_requests,
            'tutors_count'          => count($tutors),
            'schools_count'         => $schools_count,            
            'schoollar_count'       => $all_schollars_count,
            'schools'               => $schools,
        ];
        return view('moder.tutors', $data);
    }

    public function change_stud_count(Request $request)
    {
        $school_id = $request->input('school_id');

        $school = School::find($school_id);

        $grades['5']  = $request->input('grade5');
        $grades['6']  = $request->input('grade6');
        $grades['7']  = $request->input('grade7');
        $grades['8']  = $request->input('grade8');
        $grades['9']  = $request->input('grade9');
        $grades['10'] = $request->input('grade10');
        $grades['11'] = $request->input('grade11');

        $request_is_empty = true;
        foreach($grades as $key => $val) {
            if (!is_null($val))
            {
                $request_is_empty = false;
                $Scholler_count = Scholler_count::where('school_id', $school_id)->where('grade', $key)->get();
                if($Scholler_count->count() > 0) 
                {
                    $schoolar_update = $Scholler_count->first();
                    $schoolar_update->count = $val;
                    $schoolar_update->save();
                    $inserted = 'updated';
                } else {  //IF there are NO records in Base
                    $schoolar_update = Scholler_count::create([
                        'region_id' => $school->region_id,
                        'school_id' => $school->id,
                        'grade'     => $key,
                        'count'     => $val,
                    ]);
                    $inserted = 'inserted';

                }
            }
            
          }
        
        if (!$request_is_empty)
        {
            $data = [
                'school_id'   => $school_id,
                'school_name' => $school->name,
                'grades'      => $grades,
                'isset'       => $schoolar_update,
                'inserted'    => $inserted,
            ];
        } else {
            $data = [
                'school_id'   => 0,
                'school_name' => 0,
                'grades'      => 0,
                'isset'       => 0,
                'inserted'    => 0,
            ];
        }

        return json_encode($data);

    }


    public function  add_tutor_accept(Request $request)
    {
        $user_request = User_request::find($request->input('request_id'));

        $muser = $user_request->user()->first();
        //dd($muser);
        $muser->role = 3;
        $muser->save();

        $mprofile = Profile::where('user_id', $muser->id)->first();//$muser->profile->first();
        //dd($mprofile);
        $mprofile->role_id = 3;
        $mprofile->save();
        



        $user_request->delete();

        return redirect('moderator/tutors');

/*
        $user_requests = User_request::where('role', 3)->where('region_id', $region->id)->get();
        //$users = User::where('role', 3)->get();

        $schools = $region->school()->get();      
        $tutors = [];
        
        $i = 0;
        foreach ($schools as $school)
        {
            $tutors[$i]['school'] = $school;
           
           #schoolar count
           if ($school->scholler_count()->count() > 0) 
            {
                $schoollars = $school->scholler_count()->first();
                $schoollar_count_str = '5 класс: '.strval($schoollars->grade5).'; 6 класс: '.$schoollars->grade6.'; 7 класс: '
                .$schoollars->grade7.'; 8 класс:' .$schoollars->grade8.'; 9 класс: '.$schoollars->grade9
                .'; 10 класс:' .$schoollars->grade10.'; 11 класс: '.$schoollars->grade11;
                               
            } else {
                $schoollars = 0;
                $schoollar_count_str = '';
            }

            $tutors[$i]['schooler_count'] = $schoollars;
            $tutors[$i]['schooler_count_str'] = $schoollar_count_str; 
      //dd($schoollars->grade5);
      //var_dump($schoollars);

           
            $school_tutors = $school->profile()->where('role_id', 3)->get();
            
            if ($school_tutors->count() > 0)
                {
                    $scool_tutor_array = [];
                    foreach ($school_tutors as $school_tutor)
                    {                    
                        $scool_tutor_array[] = $school_tutor;
                    }
                    
                } else {
                    $scool_tutor_array = 0;
                }
            $tutors[$i]['tutors'] = $scool_tutor_array;
            //dd($tutors);
            $i++;
        
        }
        
        

        $data = [
            'region'                => $region,
            'tutors'                => $tutors,
            'user_requests'         => $user_requests,
        ];
        return view('moder.tutors', $data);        

*/


    }

    public function show_quiz(Request $request)
    {
        $user = $this->auth();
        $profile = $user->profile()->first();    
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            return back()->withInput();
        } 
        $region = $profile->region()->first();        
        $quizzes = Quiz::all();
        $data = [
            'quizzes' =>  $quizzes,
            'region'  => $region,
        ];
        return view('moder.quizzes', $data);
    }

    public function quiz($quiz_id, Request $request)
    {
        $user = $this->auth();
        $profile = $user->profile()->first();    
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            return back()->withInput();
        } 
        $region = $profile->region()->first();
        $quiz = Quiz::find($quiz_id);
        if(!$region) {
            redirect('login');
        };
        $schools = $region->school()->get();

        if ($schools->count() > 0)
        {
            //$schools_data = [][];
            $i = 0;
            foreach ($schools as $school)
            {
                $acl = $school->quiz_school_acl()->where('quiz_id', $quiz_id)->count();
                $respondents_count = $school->respondent()->count();
                $schools_data[$i]['school'] = $school;
                $schools_data[$i]['acl'] = $acl;
                $schools_data[$i]['respondents_count'] = $respondents_count;
                $i++;
            }
            
        } else {
            $schools_data = 0;
        }

        $data = [
            'schools' => $schools_data,
            'quiz'    => $quiz,
            'region'  => $region,
        ];

        return view('moder.quiz-acl', $data);
    }


    private function check_school_acl($school_id, $quiz_id)
    {
        $acl = Quiz_school_acl::where('school_id', $school_id)->where('quiz_id', $quiz_id)->get();
        if ($acl->count() > 0)
        {
            return $acl->first()->id;
        } else {
            return 0;
        }
    }

    public function quiz_change_acl_toall(Request $request)
    {
        $error = 0;
        if ($request->input('allow') == 1)
        {
            $region = Region::find($request->input('region_id'));
            $schools = $region->school()->get();
            foreach($schools as $school)
            {
                $acl = $this->check_school_acl($school->id, $request->input('quiz_id'));
                if ($acl != 0)
                {
                    $quiz_acl = Quiz_school_acl::create([
                        'quiz_id'   => $request->input('quiz_id'),
                        'school_id' => $school->id,
                    ]);
                }
            }
        }



        $data = [
            'Error'    => '0',
            'quiz_acl' => $quiz_acl,
        ];
        return back()->withInput();
    }

    public function quiz_change_acl(Request $request)
    {
        $error = 0;
        if ($request->input('allow') == 1)
        {
            $quiz_acl = Quiz_school_acl::create([
                'quiz_id'        => $request->input('quiz_id'),
                'school_id' => $request->input('school_id'),
            ]);
        } else {
            $quiz_acl = Quiz_school_acl::where('quiz_id', $request->input('quiz_id'))->where('school_id', $request->input('school_id'));
            $quiz_acl->delete();

        }

        $data = [
            'Error'    => '0',
            'quiz_acl' => $quiz_acl,
        ];
        return json_encode($data);
    }

    public function schools(Request $request)
    {
        return '';
    }

//////////////////// Tutors ///////////////////////////////////////
    public function show_tutors(Request $request)
    {

        $user = $this->auth();
        $profile = $user->profile()->first();    
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            return back()->withInput();
        } 


        $user_requests = User_request::all();
        $users = User::where('role', 3)->where('region_id', $profile->region_id)->get();

        //dd($users);
        $data = [
            'user_requests' => $user_requests,
            'users'         => $users,
        ];
        return view('admin.moderators', $data);

    }

    public function  accept_moderator(Request $request)
    {
        $user_request = User_request::find($request->input('request_id'));

        $muser = $user_request->user()->first();
        $muser->role = 2;
        $muser->save();
        
        $user_requests = User_request::all();
        $i = 0;
        foreach ($user_requests as $rqst)
        {
            
            $requests[$i]['id'] = $rqst->id;
            $requests[$i]['fio'] = $rqst->user()->first()->name;
            $requests[$i]['region'] = $rqst->region()->first()->name;
            $requests[$i]['date'] = $rqst->created_at;
            $i++;
        }
        $users = User::where('role', 2)->get();
        $i = 0;
        foreach ($users as $user)
        {
            //return json_encode($user->profile()->first()->region()->first()->name);
            $usrs[$i]['id'] = $user->id;
            $usrs[$i]['name'] = $user->name;
            $usrs[$i]['region'] = $user->profile()->first()->region()->first()->name;
        }
        $data = [
            'user_requests' => $requests,
            'users'         => $usrs,
        ];
        $user_request->delete();
        return json_encode($data);
    }

    // Check Auth
    private function auth()
    {
        if (!Auth::check()) {
            redirect('login');
        }
        return User::find(Auth::user()->id);        
    }

    // get amounts
    private function getSchoolarCount($region_id, $school_id, $grade)
    {
        if ($grade != 0)
        {
            $amount = Scholler_count::where('region_id', $region_id)->where('school_id', $school_id)->where('grade', $grade)->get('count');
        } else {
            $amount = Scholler_count::where('region_id', $region_id)->where('school_id', $school_id)->sum('count');
        }
        return $amount;
    }



    public function delete_tutor(Request $request)
    {
        $user_tutor_id = $request->input('id');

        $tutor = User::destroy($user_tutor_id);

        $data = [
            'error' => 0,
        ];
        return json_encode($data);

    }    

    public function rename_school(Request $request)
    {
        $school_id  =  $request->input('school_id');
        $new_name_ru = $request->input('name_ru');
        $new_name_kz = $request->input('name_kz');
        $school = School::find($school_id);

        if ($school === null) {
            $data = [
                'error'    => 1,
                'comment'  => 'school Not Found',
            ];
            return json_encode($data);
        }

        $school_data = [
            'ru' => [
                'name' => $new_name_ru,
                ],
            'kz' => [
                'name' => $new_name_kz,
            ],
        ];

        $school->update($school_data);
        $data = [
            'error'    => 0,
            'comment' => 'School name was updated',
        ];

        return json_encode($data);

    }

    public function show_schools(Request $request)
    {
        $user = $this->auth();
        $profile = $user->profile()->first();    
        if (($profile->role_id == 0) OR ($profile->role_id == 3) )
        {
            return back()->withInput();
        } 
        $region = $profile->region()->first();
        $schools = $region->school()->get();
        $schools_array = [];
        foreach($schools as $school)
        {
            $schools_array[]['school'] = $school;
        }        
        //dd($schools_array);

        $data = [
            'schools' => $schools_array,
            'region'  => $region,
            'error'   => 0,
        ];
        return view('moder.schools', $data);
    }



    public function change_school(Request $request)
    {
        $school_id  =  $request->input('school_id');
        $user_id  =  $request->input('user_id');
        $school = School::find($school_id);

        if ($school === null) {
            $data = [
                'error'    => 1,
                'comment'  => 'school Not Found',
            ];
            return json_encode($data);
        }

        $user = User::find($user_id);
        if ($user === null) {
            $data = [
                'error'    => 1,
                'comment'  => 'User Not Found',
            ];
            return json_encode($data);
        }        

        $profile = $user->profile()->first();
        $profile->scool_id = $school->id;
        $profile->save();

        $data = [
            'profile'  => $profile,
            'error'    => 0,
            'comment' => 'School  was updated',
        ];

        return json_encode($data);

    }    
}
