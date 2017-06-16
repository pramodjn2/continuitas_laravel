<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Role;
use App\role_user;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        if ($request) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6|max:50',
                'password_confirmation' => 'required_with:password|min:6'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 406);
            }

            // generate random code to verify
            $request['confirmation_code'] = str_random(30);

            // set default user role ID
            $request['role_id'] = 1;

            // define password
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'confirmation_code' => $request['confirmation_code'],
                'status' => 0
            ]);

            $data = $request->only('name', 'email', 'confirmation_code');

            // set user role id in relationship table
            DB::insert('insert into role_user (user_id, role_id) values (?, ?)', [$user->id, $request['role_id']]);

            // send email
            Mail::queue('emails.verify', $data, function($message) use ($data) {
                $message->to($data['email'])->subject('Verify your email address');
            });

            return response()->json(['Congratulations! Your account created. Already ngLaravel system sent a verification email.'], 200);
        } else {
            return response()->json(['error' => 'Your field save '], 401);
        }
    }


    /**
     * @param $confirmation_code
     * @return mixed
     * @throws InvalidConfirmationCodeException
     */
    public function confirm($confirmation_code)
    {
        if(!$confirmation_code)
        {
            return response()->json('Your activation code doesn\'t exist.', 406);
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if (!$user)
        {
            return response()->json('Your code doesn\'t valid.', 406);
        }

        $user->status = 1;
        $user->confirmation_code = null;
        $user->save();

        return response()->json('Your account verified successfully.', 200);
    }

}
