<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 1/19/19
 * Time: 1:40 AM
 */

namespace Qmeyti\LaravelAuth\Classes\Drivers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Qmeyti\LaravelAuth\Notification\AuthSmsNotification;

class MobileDriver implements Driver
{
    private const regex = '!^09[0-9]{9}$!';
    /**
     * Verification view file that show when user do activation his account
     */
    public const VerificationViewPath = 'qlauth::verify_sms';

    /**
     * Activation code length
     */
    public const VerificationCodeLength = 6;

    /**
     * generate encrypt with generated code
     *
     * @param $code
     * @return mixed
     */
    public static function encrypt($code)
    {
        return $code;
    }

    /**
     * check user code is true
     *
     * @param $originalCode
     * @param $userCode
     * @return bool
     */
    public static function check_code($originalCode, $userCode): bool
    {
        if (self::encrypt($originalCode) == $userCode)
            return true;
        return false;
    }

    public static function login_validation_rules()
    {
        return 'required|string|regex:' . self::regex;
    }

    public static function register_validation_rules()
    {
        return 'string|regex:' . self::regex . '|required|unique:users,mobile';
    }

    public static function update_validation_rules()
    {
        //TODO: Implement update_validation_rules() method.
    }

    public static function check_format($input): bool
    {
        return preg_match(self::regex, $input);
    }

    public static function register_notify(\Qmeyti\LaravelAuth\Models\User $user, $code)
    {
        Notification::send($user, new AuthSmsNotification($user, $code));
    }

    public static function recovery_notify(\Qmeyti\LaravelAuth\Models\User $user, $code)
    {
        Notification::send($user, new AuthSmsNotification($user, $code));
    }
}