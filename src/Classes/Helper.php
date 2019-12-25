<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 1/19/19
 * Time: 12:12 AM
 */

namespace Qmeyti\LaravelAuth\Classes;

class Helper
{
    /**
     * Get a driver
     *
     * @param $driver
     * @return mixed
     */
    public static function get_driver($driver)
    {
        return config('qlauth.driver')[$driver];
    }

    /**
     * get validation rules of every field
     *
     * @param $field
     * @return string
     */
    public static function get_validation_rules($field)
    {
        return Helper::load_driver($field)::register_validation_rules();
    }

    /**
     * go through drivers list and return data list
     *
     * @param $field
     * @return array
     */
    public static function get_driver_data_list($field)
    {
        $list = [];
        foreach (config('qlauth.driver') as $key => $item) {
            if (isset($item[$field]) && $item[$field] == true)
                $list[] = $key;
        }

        return $list;
    }

    /**
     * Check is item exists in register fields
     *
     * @param $item
     * @return bool
     */
    public static function exists_in_register_fields($item)
    {
        return in_array($item, self::register_fields());
    }

    /**
     * get list of identifiers for register
     *
     * @return array
     */
    public static function register_fields()
    {
        return self::get_driver_data_list('register');
    }

    /**
     * get list of identifiers user can login with that
     *
     * @return array
     */
    public static function login_with()
    {
        return self::get_driver_data_list('login');
    }

    /**
     * get a list of verification modes who that need to verify from configs
     *
     * @return array
     */
    public static function needs_to_verify()
    {
        return self::get_driver_data_list('verify');
    }

    /**
     * Get the mode of verification and show if need to verify
     *
     * @param $driver
     * @return bool
     */
    public static function need_to_verify($driver)
    {
        return self::get_driver($driver)['verify'];
    }

    /**
     * Get random code len from config
     *
     * @return int
     */
    public static function get_random_code_len(): int
    {
        return Helper::load_driver(config('qlauth.user_verify_with'))::VerificationCodeLength;
    }

    /**
     * create verification driver name with $verificationMode @example EmailVerificationDriver
     *
     * @param $verificationMode
     * @return string
     */
    public static function load_driver($verificationMode)
    {
        return '\Qmeyti\LaravelAuth\Classes\Drivers\\' . ucfirst(strtolower($verificationMode)) . 'Driver';
    }

    /**
     * Control user access to authentication routes after login
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function unauthorized_access()
    {
        /**
         * If user is login and active then go dashboard
         */
        if (auth()->check() && auth()->user()->active == 1) {

            return redirect()->route(config('qlauth.login_redirect'));

            /**
             * if user is inactive go activation form
             */
        } elseif (auth()->check() && auth()->user()->active == 0) {

            return redirect()->route('verification_form');
        }

    }
}