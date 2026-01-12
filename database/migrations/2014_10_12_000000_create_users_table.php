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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->set('status', ['0', '1'])->default("1");
            $table->string('contact_no',13)->nullable();
            $table->smallInteger('department_id')->nullable();
            $table->smallInteger('role_id')->nullable();
            $table->smallInteger('position_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('approver_id')->nullable();
            $table->string('weak_password', 50)->nullable();
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
