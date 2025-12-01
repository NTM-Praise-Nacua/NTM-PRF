<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('photo',255)->nullable();
            $table->string('address_line_1',255)->nullable();
            $table->string('address_line_2',255)->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('lat',255)->nullable();
            $table->string('long',255)->nullable();
            $table->set('status', ['0', '1'])->default("1");
            $table->set('is_blocked', ['0', '1'])->default("0");
            $table->string('contact_no',13)->nullable();
            $table->smallInteger('department_id')->nullable();
            $table->smallInteger('role_id')->nullable();
            $table->bigInteger('parent_user_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('approver_id')->nullable();
            $table->string('default_profile_color')->default('#565a53bf')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
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
