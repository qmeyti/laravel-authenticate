<?php

namespace Qmeyti\LaravelAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Qmeyti\LaravelAuth\Classes\Helper;
use Qmeyti\LaravelAuth\Classes\Verify;
use Qmeyti\LaravelAuth\Models\User;
use Qmeyti\LaravelAuth\Models\Verification;

/**
 * Verification system
 *
 * Class VerificationController
 * @package Qmeyti\LaravelAuth\Controllers
 */
class VerificationController extends Controller
{
    /**
     * Check user can access to the activation forms
     *
     * @return bool
     */
    private function __access()
    {
        if (auth()->check())
            if (auth()->user()->active == 0) {
                return true;
            } else {
                redirect()->route(config('qlauth.login_redirect'))->send();
            }
        else
            redirect()->route('signin_form')->send();
    }

    /**
     * return activation-form-view according to verification-mode
     *
     * @param $verificationBy
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function select_verification_view($verificationBy, $data = [])
    {
        /**
         * Get view path from identifier driver
         */
        $view = Helper::load_driver($verificationBy)::VerificationViewPath;

        return view($view, $data);
    }

    /**
     * Create verification form
     */
    public function verification_form()
    {
        $this->__access();

        $mode = config('qlauth.user_verify_with');

        /**
         * If user verification mode need to verify action then
         */
        if (Helper::need_to_verify($mode)) {

            $ver = Verification::get_the_verification($mode, auth()->user()->id);

            if (!$ver) {
                $ver = Verification::create_empty($mode);
            }


            return $this->select_verification_view($mode, [
                'verify' => false,
                'expire' => Verify::is_code_expired($ver->try_count, $ver->try_time),
                'try_count' => $ver->try_count
            ]);
        }

        /**
         * If no need verification then active user and go dashboard
         */
        User::user_active(auth()->user());
        return redirect()->route(config('qlauth.login_redirect'))->with(['status' => 'success', 'message' => __('qmauth.login successfully')]);
    }

    /**
     * Verification sent code
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verification(Request $request)
    {
        $this->__access();

        $this->validate($request, [
            'code' => 'required|string|min:3|max:191'
        ]);

        /**
         * Get verification mode
         */
        $mode = config('qlauth.user_verify_with');

        /**
         * Get user data
         */
        $user = \auth()->user();

        /**
         * Check code is true and return response array
         *
         * @see Verify::verify_response()
         */
        $result = Verify::check_code($user, $request->input('code'), $mode);

        /**
         * If user is verified then
         * set user active
         * and go to selected route for login users
         */
        if ($result['verify']) {
            /**
             * Set user Active status
             */
            User::user_active($user);

            return redirect()->route(config('qlauth.login_redirect'))->with(['status' => 'success', 'message' => __('qmauth.login successfully')]);
        }

        /**
         * If has error code then load verification form again
         */
        return $this->select_verification_view($mode, $result);
    }

    /**
     * Resend activation code
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function resend_code()
    {
        $mode = config('qlauth.user_verify_with');
        /**
         * If verification system is ON
         * and user is LOGIN
         * and user is INACTIVE
         * and current login strategy need to verification
         * then do send code action
         */
        if (config('qlauth.verify') && auth()->check() && auth()->user()->active == 0 && Helper::need_to_verify($mode)) {
            /**
             * Send code action
             */
            $res = Verify::send_code(User::find(auth()->id()));

            /**
             * If code send code action is successfully
             */
            if ($res['sent']) {

                if (\request()->ajax()) {
                    return response()->json([[
                        'status' => 'success',
                        'message' => __('qmauth.code sent successfully'),
                        'data' => [
                            /**
                             * time delay to send new code
                             */
                            'wait' => $res['wait']
                        ]
                    ]]);
                }

                return redirect()->back()->with(['status' => 'success', 'message' => __('qmauth.code sent successfully'), 'wait' => $res['wait']]);
            }
            /**
             * If code not send
             */
            if (\request()->ajax()) {
                return response()->json([[
                    'status' => 'error',
                    'message' => __('qmauth.please wait, cant send code now'),
                    'data' => [
                        'wait' => $res['wait']
                    ]
                ]]);
            }

            return redirect()->back()->with(['status' => 'warning', 'message' => __('qmauth.please wait, cant send code now'), 'wait' => $res['wait']]);
        }

        /**
         * If user access is forbidden
         */
        if (\request()->ajax()) {
            return response()->json([[
                'status' => 'error',
                'message' => __('qmauth.cant access to this url'),
                'data' => []
            ]]);
        }

        return redirect()->back()->with(['status' => 'warning', 'message' => __('qmauth.cant access to this url')]);
    }
}
