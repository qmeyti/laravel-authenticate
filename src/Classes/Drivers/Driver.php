<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 1/19/19
 * Time: 1:35 AM
 */

namespace Qmeyti\LaravelAuth\Classes\Drivers;


use Illuminate\Http\Request;

interface Driver
{
    /**
     * generate encrypt with generated code
     *
     * @param int|string $code
     * @return mixed
     */
    public static function encrypt($code);

    /**
     * check user code is true
     *
     * @param int|string $originalCode
     * @param int|string $userCode
     * @return bool
     */
    public static function check_code($originalCode, $userCode): bool;

    public static function login_validation_rules();

    public static function register_validation_rules();

    public static function register_notify(\Qmeyti\LaravelAuth\Models\User $user, $code);

    public static function recovery_notify(\Qmeyti\LaravelAuth\Models\User $user, $code);

    public static function update_validation_rules();

    public static function check_format($input): bool;

}