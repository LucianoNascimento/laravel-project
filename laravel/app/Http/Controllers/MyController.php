<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MyController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function showLogin()
    {
        return view('user.login');
    }

    public function doLogin(Request $request)
    {
        echo 'hello';
        $rules = array(
            'username' => 'required', // make sure the email is an actual email
            'password' => 'required' //|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        $form=$request->get('form');
        $username=$request->get('username');
        $password=$request->get('password');

        if ($form='signin'){
            $pass = DB::table('users')->where('username',$username)->value('password');

            $password=Hash::make($password);
            if($password != $pass){
                return view('signin')->with('userwrong','username or password wrong');
            }

        }
        return view('home')->with('username',$username);

        //$validator = Validator::make(Input::all(), $rules);

        //if ($validator->fails()) {
          //  return Redirect::to('signin')
           //     ->withErrors($validator)// send back all errors to the login form
             //   ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        //}
        //else {

            // create our user data for the authentication
            $userdata = array(
                'username' => Input::get('username'),
                'password' => Input::get('password')
            );

            // attempt to do the login
            if (Auth::attempt($userdata)) {

                // validation successful!
                // redirect them to the secure section or whatever
                // return Redirect::to('secure');
                // for now we'll just echo success (even though echoing in a controller is bad)
                echo 'SUCCESS!';

            } else {

                // validation not successful, send back to form
                return Redirect::to('signin');

            }
        //}
    }


    public function signup()
    {
        return view('user.createAccount');
    }

    public function checkuser(Request $request)
    {
        $form=$request->get('form');
        $username=$request->get('username');
        $password=$request->get('password');

        if ($form='signin'){
        $pass = DB::table('users')->where('username',$username)->value('password');

            $password=Hash::make($password);
            if($password != $pass){
                return view('signin')->with('userwrong','username or password wrong');
            }

        }
        return view('home')->with('username',$username);
    }
}