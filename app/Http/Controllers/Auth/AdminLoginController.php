<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AdminLoginController extends Controller
{

    use AuthenticatesUsers;

      public function showLoginForm()
    {
        return view('auth.admin-login');
    }


     public function login(Request $request)
    {

    //vlidate
    $this->validate($request, 
    [
        'email'=>'required|email', 
        'password' =>'required|min:5'
    ]);


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


    //attempt login and redirect intended Auth::attempt($credentials)

    if(Auth::guard('admin')->attempt( [ 'email' => $request->email, 'password' => $request->password ], $request->remember))
    {

     return redirect()->intended(route('admin.dashboard'));

    }


      // If the login attempt was unsuccessful we will increment the number of attempts
      // to login and redirect the user back to the login form. Of course, when this
      // user surpasses their maximum number of attempts they will get locked out.
      $this->incrementLoginAttempts($request);

      return $this->sendFailedLoginResponse($request);

   // return redirect()->back()->withInput($request->only('email','remember'));


    }
   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin' ,  ['except' => 'logout']);
    }


      public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }


 /**
     * Get the maximum number of attempts to allow.
     *
     * @return int
     */
    // public function maxAttempts()
    // {
    //     return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    // }

    /**
     * Get the number of minutes to throttle/block for.
     *
     * @return int
     */
    // public function decayMinutes()
    // {
    //     return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    // }


}
