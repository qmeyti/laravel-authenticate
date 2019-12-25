<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 1/19/19
 * Time: 2:37 PM
 */

namespace Qmeyti\LaravelAuth\Classes;


class Signin
{
    /**
     * Get an user input and check it and find driver name
     *
     * @param $input
     * @return string|null
     */
    public static function check_identifier_driver_mode($input)
    {
        /**
         * Get login list of drivers
         */
        $drivers = Helper::login_with();
        foreach ($drivers as $driver) {
            /**
             * If input format equal is equal by driver verification rule return driver name
             */
            if (Helper::load_driver($driver)::check_format($input)) return $driver;
        }

        return null;
    }


}