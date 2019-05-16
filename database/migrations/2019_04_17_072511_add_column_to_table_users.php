<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->bigInteger('user_role_id')->unsigned();
            $table->string('phone_number')->nullable();
            $table->string('gender');
            $table->string('dob')->nullable();
            $table->string('profile_img')->default('default.jpg');
            $table->text('more_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(['user_role_id','phone_number','gender','dob','profile_img','more_info']);
        });
    }
}
