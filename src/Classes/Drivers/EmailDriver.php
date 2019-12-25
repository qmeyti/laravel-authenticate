<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 1/19/19
 * Time: 1:41 AM
 */

namespace Qmeyti\LaravelAuth\Classes\Drivers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Support\Facades\Notification;
use Qmeyti\LaravelAuth\Notification\AuthMailNotification;

class EmailDriver implements Driver
{
    /**
     * Verification view file that show when user do activation his account
     */
    public const VerificationViewPath = 'qlauth::verify_email';

    /**
     * Activation code length
     */
    public const VerificationCodeLength = 10;

    /**
     * generate encrypt with generated code
     *
     * @param $code
     * @return mixed
     */
    public static function encrypt($code)
    {
        return hash('sha512', $code . config('qlauth.secret_key'));
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
        return 'required|email|string|max:191';
    }

    public static function register_validation_rules()
    {
        return 'email|string|required|max:191|unique:users,email';
    }

    public static function update_validation_rules()
    {
        //TODO: Implement update_validation_rules() method.
    }

    public static function check_format($input): bool
    {
        return (bool)filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    public static function register_notify(\Qmeyti\LaravelAuth\Models\User $user, $code)
    {
        $message = (new MailMessage)
            ->line('ایمیل فعالسازی حساب کاربری شما')
            ->action('جهت فعالسازی حساب کاربری روی لینک کلیک کنید.', route('verification', ['code' => $code]))
            ->line('با تشکر از شما به جهت ثبت نام در این سامانه.');

        Notification::send($user, new AuthMailNotification($user, $message));
    }

    public static function recovery_notify(\Qmeyti\LaravelAuth\Models\User $user, $code)
    {
        $message = (new MailMessage)
            ->line('ایمیل بازیابی رمز عبور حساب کاربری شما')
            ->action('جهت بازیابی رمز عبور خود بر روی لینک کلیک کنید.', route('recovery_reset_form', ['code' => $code]))
            ->line('با تشکر از شما کاربر عزیز.');

        Notification::send($user, new AuthMailNotification($user, $message));
    }
}