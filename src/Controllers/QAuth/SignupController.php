<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 2/21/19
 * Time: 2:06 PM
 */

namespace App\Http\Controllers\QAuth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Qmeyti\LaravelAuth\Classes\Helper;
use Qmeyti\LaravelAuth\Models\User;

class SignupController extends Controller
{
    public function validation(Request $request)
    {
        if ($request->has('name')) {
            $request->merge(['name' => htmlentities(strip_tags($request->input('name')), ENT_QUOTES, 'UTF-8', false)]);
        }

        $rules = [
            "name" => "string|max:191|min:2|required",
            "password" => "string|max:191|min:" . config('qlauth.password_min_len') . "|required|confirmed",
            //todo captcha
        ];

        if (Helper::exists_in_register_fields('nationalcode')) {
            $rules["nationalcode"] = Helper::get_validation_rules('nationalcode');
        }

        if (Helper::exists_in_register_fields('email')) {
            $rules["email"] = Helper::get_validation_rules('email');
        }

        if (Helper::exists_in_register_fields('mobile')) {
            $rules["mobile"] = Helper::get_validation_rules('mobile');
        }

        if (Helper::exists_in_register_fields('username')) {
            $rules["username"] = Helper::get_validation_rules('username');
        }

        $this->validate($request, $rules);
    }

    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->password = bcrypt($request->input('password'));
        $user->nationalcode = $request->input('nationalcode', null);
        $user->email = $request->input('email', null);
        $user->mobile = $request->input('mobile', null);
        $user->username = $request->input('username', null);
        $user->save();

        return $user;
    }
}