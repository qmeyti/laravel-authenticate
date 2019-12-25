<?php

return [

    /**
     * Where to redirect users after logout.
     *
     * @var string
     */
    'logout_route' => 'welcome',

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    'login_redirect' => 'home',

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    'register_redirect' => 'home',

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    'verification_redirect' => 'home',

    /**
     * minimum len of password
     */
    'password_min_len' => 6,

    /**
     * Check password in login
     * @notice if config was false password field in login will be disable
     */
    'enable_password_check' => true,

    /**
     * login key type
     * default values is [sms, secret]
     * if `sms` is enable then secret key send to mobile and user enter that
     * if `secret` is enable user enter own password
     */
    'key_type' => 'secret',

    /**
     * when this method is enable
     * password was encrypt
     * and send to server in two steps
     */
    'safe_login' => false,

    /**
     * Enable verification after register
     *
     * @var bool
     */
    'verify' => true,

    /**
     * Secret key for create encryption
     */
    'secret_key' => '(-:###MEHDI-HAJATPOUR###:-)',

    /**
     * Identifiers driver list
     */
    'driver' => [
        'email' => [
            /**
             * need to verification action
             */
            'verify' => true,
            'login' => true,
            'register' => true,
            'title' => 'email',
        ],
        'username' => [
            'verify' => false,
            'login' => true,
            'register' => true,
            'title' => 'username',
        ],
        'mobile' => [
            'verify' => true,
            'login' => true,
            'register' => true,
            'title' => 'mobile',
        ],
        'nationalcode' => [
            'verify' => false,
            'login' => true,
            'register' => true,
            'title' => 'national code',
        ]
    ],

    /** ********************* **
     ** Verify user by        **
     ** ********************* **/
    'user_verify_with' => 'email',

    'maximum_number_of_messages_sent_per_set' => 3,

    'delay_between_send_two_codes' => 15,

    'delay_between_send_set_codes' => 30,

    /**
     * Life time of every verification code
     */
    'code_life_time' => 1000,

    /**
     * Max try to enter fail code
     * code will expired after the max try
     */
    'max_try_to_enter_code' => 3,

];
