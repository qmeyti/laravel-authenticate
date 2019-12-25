<?php

namespace Qmeyti\LaravelAuth\Models;

use App\User as MainUser;
use Qmeyti\LaravelAuth\Classes\Helper;

/**
 * Create new instance of user model with user custom fields
 *
 * Class User
 * @package Qmeyti\LaravelAuth\Models
 */
final class User extends MainUser
{
    /**
     * Set table name
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Singleton instance
     *
     * @var User
     */
    private static $instance;

    public function __construct()
    {
        /**
         * Singleton new instance
         */
        if (!self::$instance) {

            /**
             * Merge parent model $fillable fields with new register fields
             */
            $this->fillable = array_merge($this->fillable, Helper::register_fields());

            $this->fillable[] = 'active';

            self::$instance = $this;
        }
        return self::$instance;
    }

    /**
     * One to Many relation to verifications table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }

    /**
     * active user
     *
     * @param $user
     */
    public static function user_active($user)
    {
        $user->active = 1;
        $user->save();
    }
}
