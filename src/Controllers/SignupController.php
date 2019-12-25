<?php

namespace Qmeyti\LaravelAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Qmeyti\LaravelAuth\Classes\Helper;
use Qmeyti\LaravelAuth\Models\User;

class SignupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Create user register form page
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::unauthorized_access();

        return view('qlauth::register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        Helper::unauthorized_access();

        $SignUp = new \App\Http\Controllers\QAuth\SignupController();

        $SignUp->validation($request);

        $user = $SignUp->register($request);

        /**
         * Login user
         */
        Auth::login($user);

        /**
         * If verification system is on and current model need to verify
         * then go to verification-form
         */
        if (config('qlauth.verify') && Helper::need_to_verify(config('qlauth.user_verify_with'))) {

            $code = \Qmeyti\LaravelAuth\Classes\Verify::send_code($user);

            return redirect()->route('verification_form');
        }

        /**
         * If no need activation then active user and go dashboard
         */
        User::user_active($user);

        return redirect()->route(config('qlauth.register_redirect'))->with(['status' => 'success', 'message' => __('qmauth.login successfully')]);
    }
}
