<?php

namespace App\Http\Controllers\Auth;

use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Auth\Userverification;
use App\Notifications\SendUserVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'user/user-dasboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }


    protected function createToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //user created

       $user=User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);


     return $user;

    }


    public function userVerificationCreate($user)
    {

    return Userverification::create([
        'user_id' => $user->id,
        'token' => $this->createToken(),
       ]);

    }



    public function  sendVerifyEmail($user)
    {
      

   return $user->notify(new SendUserVerifyEmail($user)) ;


    }





    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        

     //get data into verification
     
     $this->userVerificationCreate($user);


       //send email

     $this->sendVerifyEmail($user);

       
    $this->guard()->logout();
    return redirect()->route('login')->with('success', 'We have sent you an account activation link through email. Please activate your account to login.');
       

    }


  public function verify($email=null,$token=null)
  {
    if($email && $token)
    {
    $user=User::where('email',$email)->first();
    $verify=$user->Userverification->token;
    if(!is_null($verify) && !is_null($user))
    {
    
       if(!$user->verified)
        {
            $user->verified=1;
            $user->save();
            $status="Your Email is verified. You can login now!";

        }
        else
        {
            $status="Already verified. Please login!";
        }

    }else{

        if($email)
        {
        $user->where('email',$email)->delete();
        }

        return redirect()->route('login')->with('warning','Sorry, Your email can\'t be verified. Please try again.');

    }
    }else{

        if($email)
        {
          User::where('email',$email)->delete();
        }

        redirect()->route('login')->with('warning','Invalid credentials. Please try again.');
    }

   return redirect()->route('login')->with('success', $status);
  
    }
    

}
