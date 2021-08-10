<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Notifications\MailHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\FrontUser;
use App\Models\FrontUserUnlogged;
use App\Models\Country;
use Socialite;
use Session;

class AuthController extends Controller
{
    public function renderLogin()
    {
        return redirect()->route('home');
    }
    /**
     *  Login User
     */
    public function login(Request $request)
    {
        $data['redirect'] = url('/'.$this->lang->lang.'/account/cart');

        if (@$_COOKIE['redirect_status']) {
            $data['redirect'] = url('/'.$this->lang->lang.'/order');
            setcookie('redirect_status', 0, time() + 10000000, '/');
        }

        $validator = validator($request->all(), [
            'email' => 'required',
            'password' => 'required|min:4'
        ]);

        if ($validator->fails()) {
            $data['status'] = 'false';
            $data['errors'] = $validator->errors()->all();
            return $data;
        }

        if (Auth::guard('persons')->attempt($request->all())) {
            $data['status'] = 'true';
        }else {
            $data['status'] = 'false';
            $data['errors'] = [trans('front.login.error')];
        }

        return $data;
    }

    /**
     * Register User
     */
    public function register(Request $request)
    {
        $code = $request->get('code');
        $data['redirect'] = url('/'.$this->lang->lang.'/account/cart');

        if (@$_COOKIE['redirect_status']) {
            $data['redirect'] = url('/'.$this->lang->lang.'/order');
            setcookie('redirect_status', 0, time() + 10000000, '/');
        }

        $validator = validator($request->all(), [
            'email' => 'required|unique:front_users',
            'name'  => 'required|min:3',
            'phone' => 'required|min:3',
            'password' => 'required|min:4',
            'agree' => 'required'
        ]);

        if ($validator->fails()) {
            $data['status'] = 'false';
            $data['errors'] = $validator->errors()->all();
            return $data;
        }

        $user = FrontUser::create([
            'lang_id'       => $this->lang->id,
            'country_id'    => $this->country->id,
            'currency_id'   => $this->currency->id,
            'name'          => $request->get('name'),
            'phone'         => '+'.@$code['phone_code'].' '.$request->get('phone'),
            'email'         => $request->get('email'),
            'customer_type' => $request->get('consumerType'),
            'company'       => $request->get('company'),
            'password'      => bcrypt($request->get('password')),
            'remember_token'=> $request->get('_token')
        ]);

        Auth::guard('persons')->login($user);

        $data['name'] = $user->name;
        $subject = trans('vars.Email-templates.emailRegistrationSubject').'info@soledy.com';
        $template = "mails.auth.register";

        $mail = new MailHandler();
        $mail->sendEmail($data, $request->get('email'), $subject, $template);

        return $data;
        // return response()->json(['user'=> Auth::guard('persons')->user()], 200);
    }

    /**
     * Login as Guest
     */
    public function loginAsGuest(Request $request)
    {
        $frontUser = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();

        if (is_null($frontUser)) {
            $frontUser = FrontUserUnlogged::create([
                'user_id' => @$_COOKIE['user_id'],
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'lang_id'       => $this->lang->id,
                'country_id'    => $this->country->id,
                'currency_id'   => $this->currency->id,
            ]);
        }

        $data['status'] = 'true';
        return $data;
    }

    /**
    *  Authorization with google and facebook
    */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
    *  Callback authorization with google and facebook
    */
    public function handleProviderCallback($provider)
    {
        $redirect = 'false';

        if (@$_COOKIE['redirect_status']) {
            $redirect = url('/'.$this->lang->lang.'/order');
            setcookie('redirect_status', 0, time() + 10000000, '/');
        }

        $user = Socialite::driver($provider)->user();
        $checkUser = FrontUser::where('email', $user->getEmail())->first();

        if (is_null($checkUser)) {
            $authUser = FrontUser::where($provider, $user->getId())->first();
            if (is_null($authUser)) {
                $authUser = FrontUser::create([
                    'lang_id'       => $this->lang->id,
                    'country_id'    => $this->country->id,
                    'currency_id'   => $this->currency->id,
                    'email'         => $user->getEmail(),
                    'name'          => $user->getName(),
                     $provider      => $user->getId(),
                    'remember_token' => $user->token,
                ]);
            }
        }else{
            $checkUser->update([ $provider => $user->getId() ]);
            $authUser = FrontUser::where('email', $user->getEmail())->first();
        }

        Auth::guard('persons')->login($authUser);

        if (@$_COOKIE['redirect_status']) {
            setcookie('redirect_status', 0, time() + 10000000, '/');
            return redirect('/' . @$_COOKIE['lang_id'] . '/order');
        }

        return redirect('/' . @$_COOKIE['lang_id'] . '/account/cart');
    }

    /**
     * Logout User
     */
    public function logout()
    {
        Auth::guard('persons')->logout();

        setcookie('promocode', '', time() + 10000000, '/');

        return redirect('/'.$this->lang->lang);
    }


    /*******************************   Forget Password   ***********************
     * Send code to mail
     */
    public function sendEmailCode(Request $request)
    {
        $user = FrontUser::where('email', $request->get('email'))->first();

        if (!is_null($user)) {
            session()->put(['code' => str_random(4), 'user_id' => $user->id]);
            $data["code"] = session('code');
            $subject = "Reset Password";
            $template = "mails.auth.forgetPassword";

            $mail = new MailHandler();
            $mail->sendEmail($data, $request->get('email'), $subject, $template);
            $data['status'] = "true";
        }else{
            $data['status'] = "false";
            $data['error'] = trans('front.forgotPass.error');
        }

        return $data;
    }

    /**
     * Confirm code
     */
    public function confirmEmailCode(Request $request)
    {
        $validator = validator($request->all(), [
            'code' => 'required|in:'.session('code')
        ]);

        if ($validator->fails()) {
            $data['status'] = 'false';
            $data['error'] = $validator->errors()->all();
            return $data;
        }

        $data['status'] = 'true';
        return $data;
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = validator($request->all(), [
            'password' => 'required|min:3',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()], 400);
        }

        $user = FrontUser::find(session('user_id'));

        if(!is_null($user)){
            $user->password = bcrypt($request->get('password'));
            $user->remember_token = $request->get('_token');
            $user->save();

            session()->forget('code');
            session()->forget('user_id');

            $data['status'] = "true";
            Auth::guard('persons')->login($user);
        }else{
            $data['status'] = "false";
        }

        return $data;
    }

    public function checkAuth()
    {
        $userdata = FrontUser::find(Auth::guard('persons')->id());
        return response()->json(['userdata' => $userdata]);
    }

    public function getPhoneCodesList()
    {
        $data['countries'] = Country::where('active', 1)->get();

        $currentCountry = Country::where('id', @$_COOKIE['country_id'])->first();
        if (is_null($currentCountry)) {
            $currentCountry = Country::where('main', 1)->first();
        }

        $data['currentCountry'] = $currentCountry;

        return $data;
    }
}
