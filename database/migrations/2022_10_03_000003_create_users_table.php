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
        // Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreignId('country_id')->references('id')->on('countries');
            $table->string('fname');
            $table->string('lname');
            $table->string('nationality');
            $table->string('email')->unique();
            $table->enum('gender',['0','1','2'])->comment('0=>male;1=>female;2=>others');
            $table->string('password');
            $table->date('dob');
            $table->bigInteger('mobile');
            $table->enum('status',['0','1'])->default('1')->comment('0=>Inactive;1=>active');
            $table->softDeletes();
            $table->timestamps();
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
