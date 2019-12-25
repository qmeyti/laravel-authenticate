<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 1/19/19
 * Time: 4:02 PM
 */

namespace Qmeyti\LaravelAuth\Classes\Drivers;


use Illuminate\Http\Request;

class NationalcodeDriver implements Driver
{

    private const regex = '/^\d{10}$/';

    /**
     * generate encrypt with generated code
     *
     * @param int|string $code
     * @return mixed
     */
    public static function encrypt($code)
    {
        // TODO: Implement encrypt() method.
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
        //TODO: Implement check_code() method.
    }

    public static function login_validation_rules()
    {
        return 'required|string|regex:' . self::regex;
    }

    public static function register_validation_rules()
    {
        return 'required|string|regex:' . self::regex . '|unique:users,nationalcode';
    }

    public static function update_validation_rules()
    {
        // TODO: Implement update_validation_rules() method.
    }

    public static function check_format($input): bool
    {
        # check if input has 10 digits that all of them are not equal
        if (!preg_match(self::regex, $input)) {
            return false;
        }

        $check = (int)$input[9];
        $sum = array_sum(array_map(function ($x) use ($input) {
                return ((int)$input[$x]) * (10 - $x);
            }, range(0, 8))) % 11;

        return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
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