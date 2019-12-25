<?php

namespace Qmeyti\LaravelAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Qmeyti\LaravelAuth\Classes\Drivers\EmailDriver;
use Qmeyti\LaravelAuth\Classes\Helper;
use Qmeyti\LaravelAuth\Classes\Verify;
use Qmeyti\LaravelAuth\Models\User;


class RecoveryPasswordController extends Controller
{
    public function recovery_form()
    {
        Helper::unauthorized_access();

        session()->forget(['recovery_step', 'recovery_create_at', 'recovery_code']);

        return view('qlauth::passwords.email');
    }

    public function recovery(Request $request)
    {
        Helper::unauthorized_access();

        session()->forget(['recovery_create_at', 'recovery_code', 'recovery_email']);
        session()->save();

        $this->validate($request, [
            'email' => 'string|max:191|required|email'
        ]);

        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if ($user) {
            $code = Verify::generate_code(40);
            session()->put(['recovery_create_at' => time(), 'recovery_code' => $code, 'recovery_email' => $email]);
            session()->save();

            EmailDriver::recovery_notify($user, $code);

            return redirect()->route('recovery_message');
        }

        return redirect()->back()->with(['status' => 'info', 'message' => __('qmauth.recovery email send to you')]);
    }

    public function recovery_message()
    {
        if (!session()->exists('recovery_code')) {
            abort(404);
        }

        Helper::unauthorized_access();

        return view('qlauth::passwords.message');
    }

    public function recovery_reset_form(Request $request)
    {
        if (!$request->has('code')) {
            session()->forget(['recovery_create_at', 'recovery_code', 'recovery_email']);
            return redirect()->route('recovery')->with(['status' => 'success', 'message' => __('qmauth.access forbidden')]);
        }

        if (!session()->exists('recovery_code')) {
            return redirect()->route('recovery')->with(['status' => 'success', 'message' => __('qmauth.recovery code not exists')]);
        }

        if (session()->get('recovery_code') != $request->input('code')) {
            session()->forget(['recovery_create_at', 'recovery_code', 'recovery_email']);
            return redirect()->route('recovery')->with(['status' => 'success', 'message' => __('qmauth.code expired')]);
        }

        $user = User::where('email', session()->get('recovery_email'))->first();

        if (!session()->has('recovery_user_id') && $user) {

            session()->put(['recovery_user_id' => $user->id]);
        } elseif (!$user) {

            session()->forget(['recovery_create_at', 'recovery_code', 'recovery_email']);

            return redirect()->route('recovery')->with(['status' => 'success', 'message' => __('qmauth.access forbidden')]);
        }

        return view('qlauth::passwords.reset', []);
    }

    public function recovery_reset(Request $request)
    {

        if (!session()->has('recovery_user_id')) {
            session()->forget(['recovery_create_at', 'recovery_code', 'recovery_email']);
            return redirect()->route('recovery')->with(['status' => 'danger', 'message' => __('qmauth.access forbidden')]);
        }

        $this->validate($request, [
            'email' => 'email|string|required|max:191',
            'password' => 'confirmed|string|required|max:191|min:' . config('qlauh.password_min_len'),
        ]);
        if (session()->get('recovery_email') != $request->input('email')) {

            session()->forget(['recovery_create_at', 'recovery_code', 'recovery_email', 'recovery_user_id']);
            return redirect()->route('recovery')->with(['status' => 'success', 'warning' => __('qmauth.email is not true')]);

        }

        $user = User::find(session()->get('recovery_user_id'));

        if ($user) {

            $user->password = bcrypt($request->input('password'));

            $user->save();

        }

        session()->forget(['recovery_create_at', 'recovery_code', 'recovery_email', 'recovery_user_id']);

        return redirect()->route('signin')->with(['status' => 'success', 'message' => __('qmauth.password reset successfully')]);

    }
}
