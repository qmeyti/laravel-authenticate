<?php

namespace Qmeyti\LaravelAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Qmeyti\LaravelAuth\Classes\Helper;
use Qmeyti\LaravelAuth\Classes\Signin;
use Qmeyti\LaravelAuth\Models\User;

class SigninController extends Controller
{

    /**
     * Sing-in to system
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signin(Request $request)
    {
        Helper::unauthorized_access();

        $this->validate($request, [
            'identifier' => 'string|max:191|min:1',
            "password" => "string|max:191|min:" . config('qlauth.password_min_len') . "|required",
        ]);

        /**
         * Get driver name
         */
        $driver = Signin::check_identifier_driver_mode($request->input('identifier'));

        /**
         * If input driver not exists then return error message
         */
        if ($driver === null) {
            return redirect()->back()->with(['status' => 'danger', 'message' => __('qmauth.your information is not true')]);
        }

        /**
         * Get user by identifier
         */
        $user = User::where($driver, $request->input('identifier'))->first();

        /**
         * Check password and user name
         */
        if ($user && Hash::check($request->input('password'), $user->password) && $user->active == 1) {

            Auth::login($user);

            return redirect()->route(config('qlauth.login_redirect'))->with(['status' => 'success', 'message' => __('qmauth.login successfully')]);
        } /**
         * If user is inactive then go activation form
         */ elseif ($user && $user->active == 0) {
             Auth::login($user);
             return redirect()->route('verification_form');
        }

        return redirect()->back()->with(['status' => 'danger', 'message' => __('qmauth.your information is not true')]);
    }

    /**
     * Show sign-in form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function signin_form()
    {
        Helper::unauthorized_access();

        return view('qlauth::login', ['identifiers' => Helper::login_with()]);
    }

    /**
     * do sign-out action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signout()
    {
        Auth::logout();
        return redirect()->route(config('qlauth.logout_route'));
    }
}
