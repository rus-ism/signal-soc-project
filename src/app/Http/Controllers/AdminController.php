<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuizImport;
//use App\Imports\User;
use App\Models\User;
use App\Models\User_request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function testimportvew()
    {
        return view('admin.testimpor');
    }
    public function testimport(Request $request){
        $data = Excel::import(new QuizImport, $request->file('file')->store('files'));

        return 'OK';//redirect()->back();
    } 
    
    public function show_moderators(Request $request)
    {
        $user_requests = User_request::where('role', 2)->get();
        $users = User::where('role', 2)->get();

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
        
        $user_requests = User_request::where('role', 2)->get();
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

    public function redirect403()
    {
        return view('error.403');
    }

}
