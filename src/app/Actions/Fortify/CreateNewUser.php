<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Profile;
use App\Models\School;
use App\Models\User_request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {

       // dd($input['role']);
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
             //   'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'role'     => ['required', 'string'],
        ])->validate();
        //dd($input);
        //if (! isset($input['name']))
        $school_name = School::find($input['school_id'])->name;
       // dd($school_name);
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role'     => 5,// $input['role'],
        ]);


        $profile = Profile::create([
            'user_id'           =>      $user->id,
            'role_id'           =>      5,//$input['role'],
            'region_id'         =>      $input['region'],
            'scool_id'          =>      $input['school_id'],
            'scool_name'        =>      $school_name,
            'grade'             =>      null,
            'fio'               =>      $input['name'],            
            ]);
          //  dd($input['role']);
        $user_request = User_request::create([
            'user_id'           =>      $user->id,
            'profile_id'        =>      $profile->id,
            'region_id'         =>      $input['region'],
            'role'              =>      $input['role'],
        ]);
        $user_request->role = $input['role'];
        $user_request->save();
        return $user;

    }
}
