<?php

namespace Qmeyti\LaravelAuth\Classes;

use Qmeyti\LaravelAuth\Models\Verification;

class Verify
{
    /**
     * Send code to user
     *
     * @param $user
     * @return array
     * @throws \Exception
     */
    public static function send_code($user)
    {
        $verify = self::verify_code($user);

        $wait = self::get_wait_time($verify['verification']);

        $mode = $verify['verification']->mode;

        if ($verify['send_permit']) {

            /**
             * Generate code hashed
             */
            $encryptCode = Helper::load_driver($mode)::encrypt($verify['verification']->code);

            /**
             * Send Code
             */
            Helper::load_driver($mode)::register_notify($user, $encryptCode);
        }

        return ['wait' => $wait, 'sent' => $verify['send_permit'], 'code' => $verify['verification']->code];
    }

    /**
     * Get wait time
     *
     * @param Verification $ver
     * @return \Illuminate\Config\Repository|int|mixed
     */
    public static function get_wait_time(Verification $ver)
    {
        $spend = time() - $ver->send_time;
        $wait = 0;
        if ($ver->send_count >= config('qlauth.maximum_number_of_messages_sent_per_set')) {
            $wait = config('qlauth.delay_between_send_set_codes') - $spend;
        } else {
            $wait = config('qlauth.delay_between_send_two_codes') - $spend;
        }

        return (($wait <= 0) ? 0 : $wait);
    }

    /**
     * Generate new verification code & send permission
     *
     * @param $user
     * @return array
     * @throws \Exception
     */
    public static function verify_code($user)
    {
        /**
         * Get verification type config
         */
        $verify_with = config('qlauth.user_verify_with');

        /**
         * Get user verification information from storage
         */
        $ver = Verification::where('mode', $verify_with)->where('user_id', $user->id)->first();

        /**
         * If infos exists
         */
        if ($ver) {
            /**
             * Get difference between last send code to now
             */
            $diff = time() - $ver->send_time;

            if ($ver->send_count < config('qlauth.maximum_number_of_messages_sent_per_set') && $diff >= config('qlauth.delay_between_send_two_codes')) {

                $ver->send_count++;

            } elseif ($ver->send_count >= config('qlauth.maximum_number_of_messages_sent_per_set') && $diff >= config('qlauth.delay_between_send_set_codes')) {

                $ver->send_count = 1;

            } else {

                return ['verification' => $ver, 'send_permit' => false];
            }

            $ver->send_time = time();
            $ver->try_time = time();
            $ver->try_count = 0;
            $ver->code = self::generate_code(Helper::get_random_code_len());
            $ver->save();

            return ['verification' => $ver, 'send_permit' => true];
        }

        /**
         * if verification infos not exists
         */
        $ver = Verification::create([
            'user_id' => $user->id,
            'send_count' => 0,
            'send_time' => time(),
            'code' => random_int(100000, 999999),
            'mode' => $verify_with,
            'try_count' => 0,
            'try_time' => time()
        ]);

        return ['verification' => $ver, 'send_permit' => true];
    }

    /**
     * Create random code
     *
     * @param int $len
     * @return \Illuminate\Contracts\Routing\UrlGenerator|int|string
     * @throws \Exception
     */
    public static function generate_code(int $len = 6)
    {
        /**
         * Throw error if $len is less than 2
         */
        if ($len < 2) {
            throw new \Exception('Length of code not be less than 2 chars', 10000);
        }

        /**
         * Generate first char of code
         */
        $rand = (string)random_int(1, 9);
        /**
         * Generate second code to next
         */
        for ($i = 0; $i < $len; $i++) {
            $rand .= (string)random_int(0, 9);
            random_int(0, 1000);//Is joke ;-)
        }

        return $rand;
    }


    /**
     * Check user access after login
     */
    public static function access()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            /**
             * If verification is enable
             */
            if (config('qlauth.verify')) {
                /**
                 * If user is active by each of user verifications strategies
                 */
                if (\Illuminate\Support\Facades\Auth::user()->active == 0) {
                    /**
                     * Go to verification for if user is in-active
                     */
                    redirect()->route('verification_form')->send();
                }
            }
        } else {
            redirect()->route('signin_form')->send();
        }
    }


    /**
     * Check user sent code is valid
     *
     * @param \App\User $user
     * @param string $code
     * @param string $mode
     * @return array
     * @throws \Exception
     */
    public static function check_code(\App\User $user, string $code, string $mode): array
    {
        /**
         * Get verification data from storage
         */
        $ver = Verification::get_the_verification($mode, $user->id);
        /**
         * Check data is exists
         */
        if ($ver) {
            /**
             * Check Code
             */
            $checkCode = Helper::load_driver($mode)::check_code($ver->code, $code);

            /**
             * If code is true and not expired
             */
            if (!self::is_code_expired($ver->try_count, $ver->try_time) && $checkCode) {
                return self::verify_response($ver, true, true);

                /**
                 * If code is fail but not expired
                 */
            } elseif (!self::is_code_expired($ver->try_count, $ver->try_time)) {
                return self::verify_response($ver, false, false);

                /**
                 * If code is fail and expired
                 */
            } else {
                return self::verify_response($ver, false, true);
            }
        }

        /**
         * If verification request not exists
         * then create a new verification for this user and keep
         */
        $ver = Verification::create_empty($mode);

        return self::verify_response($ver, false, true);
    }


    /**
     * Generate response to verification request like:
     * @example ['verify', 'expire', 'try_count']
     * `verify` say the code is true and user is active
     * `expire` say the code is expired
     * `try_count` say count of tries to enter verification code
     *
     *
     * @param Verification $ver
     * @param bool $verify
     * @param bool $expire
     * @return array
     * @throws \Exception
     */
    private static function verify_response($ver, bool $verify, bool $expire): array
    {
        /**
         * If code is expired
         */
        if ($expire === true) {
            /**
             * Rest code to random other code
             */

            $ver->code = self::generate_code(Helper::get_random_code_len());
            $ver->send_time = time();
            $ver->send_count = 0;
            $ver->try_count = 0;
            /**
             * If code is unexpired increase try-count
             */
        } else {
            $ver->try_count++;
        }
        /**
         * Update try time
         */
        $ver->try_time = time();

        /**
         * Change verify status if code is verified
         */
        $ver->verify = ($verify === true) ? 1 : 0;

        $ver->save();

        return ['verify' => $verify, 'expire' => $expire, 'try_count' => $ver->try_count];
    }

    /**
     * check code has expired or no
     *
     * @param int $tryCount
     * @param int $lastTryTime
     * @return bool
     */
    public static function is_code_expired(int $tryCount, int $lastTryTime): bool
    {
        $diff = time() - $lastTryTime;

        if ($diff >= config('qlauth.code_life_time') || $tryCount >= config('qlauth.max_try_to_enter_code')) {
            return true;
        }
        return false;
    }
}