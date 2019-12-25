<?php

namespace Qmeyti\LaravelAuth\Models;

use Illuminate\Database\Eloquent\Model;
use Qmeyti\LaravelAuth\Classes\Verify;
use Qmeyti\LaravelAuth\Classes\Helper;

class Verification extends Model
{
    public $timestamps = false;

    protected $table = 'verifications';

    protected $fillable = ['user_id', 'send_count', 'send_time', 'code', 'mode', 'try_count', 'try_time', 'verify'];


    /**
     * select verification model and get the user verification data
     *
     * @param $mode
     * @param $userId
     * @return mixed
     */
    public static function get_the_verification(string $mode, int $userId)
    {
        return static::where('mode', $mode)->where('user_id', $userId)->first();
    }

    /**
     * One to Many relation to users table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate empty verification row
     *
     * @param $mode
     * @return mixed
     * @throws \Exception
     */
    public static function create_empty($mode)
    {
        return self::create([
            'user_id' => auth()->user()->id,
            'send_count' => 0,
            'send_time' => time(),
            'code' => Verify::generate_code(Helper::get_random_code_len()),
            'mode' => $mode,
            'try_count' => 0,
            'try_time' => time(),
            'verify' => 0
        ]);

    }
}
