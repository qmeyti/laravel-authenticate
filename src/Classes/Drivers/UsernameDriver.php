<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 1/19/19
 * Time: 4:00 PM
 */

namespace Qmeyti\LaravelAuth\Classes\Drivers;


use Illuminate\Http\Request;

class UsernameDriver implements Driver
{

    private const regex = '!^[a-zA-Z]{1}[a-zA-Z0-9_\-]{2,38}$!';

    /**
     * generate encrypt with generated code
     *
     * @param int|string $code
     * @return mixed
     */
    public static function encrypt($code)
    {
        //TODO: Implement encrypt() method.
    }

    /**
     * check user code is true
     *
     * @param int|string $originalCode
     * @param int|string $userCode
     * @return bool
     */
    public static function check_code($originalCode, $userCode): bool
    {
        // TODO: Implement check_code() method.
    }

    public static function login_validation_rules()
    {
        return 'required|string|regex:' . self::regex . '|max:40|min:3';
    }

    public static function register_validation_rules()
    {
        return 'required|string|regex:' . self::regex . '|max:40|min:3|unique:users,username';
    }

    public static function update_validation_rules()
    {
        // TODO: Implement update_validation_rules() method.
    }

    public static function check_format($input): bool
    {
        return preg_match(self::regex, $input);
    }

    public static function register_notify(\Qmeyti\LaravelAuth\Models\User $user, $code)
    {
        // TODO: Implement register_notify() method.
    }

    public static function recovery_notify(\Qmeyti\LaravelAuth\Models\User $user, $code)
    {
        // TODO: Implement register_notify() method.
    }
}