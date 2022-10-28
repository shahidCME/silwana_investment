<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('investments');
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('schema_id')->references('id')->on('schemas')->onDelete('cascade');
            $table->date('start_date');
            $table->float('amount',15, 2);
            $table->integer('tenure');
            $table->enum('return_type', ['0', '1'])->default('0')->comment('0=>monthly;1=>yearly'); // 0 =>monthly 1=>yearly 
            $table->float('return_percentage');
            $table->string('contract_reciept');
            $table->string('investment_doc');
            $table->string('other_doc')->nullable();
            $table->enum('status', ['0','1','2'])->default('1')->comment('0=>rejected;1=>approved;2=>pending'); // 0 =>rejected 1=>approved 2 => pending 
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
        Schema::dropIfExists('investments');
    }
}
