<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Qmeyti\LaravelAuth\Classes\Helper;

class CreateExtraUserFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'active')) {
            Schema::table('users', function ($table) {
                $table->boolean('active')->default(false);
            });
        }

        if (!Schema::hasColumn('users', 'mobile')) {
            Schema::table('users', function ($table) {
                if (Helper::exists_in_register_fields('mobile')) {
                    $table->string('mobile', 20)->index()->unique()->nullable();
                }
            });
        }

        if (!Schema::hasColumn('users', 'nationalcode')) {
            Schema::table('users', function ($table) {
                if (Helper::exists_in_register_fields('nationalcode')) {
                    $table->string('nationalcode', 20)->index()->unique()->nullable();
                }
            });
        }

        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function ($table) {
                if (Helper::exists_in_register_fields('username')) {
                    $table->string('username', 40)->index()->unique()->nullable();
                }
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
