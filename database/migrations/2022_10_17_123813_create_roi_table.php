<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('roi');
        Schema::create('roi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_id')->references('id')->on('investments')->onDelete('cascade');
            $table->float('return_amount')->nullable();
            $table->date('date_of_return');
            $table->enum('status', ['0', '1'])->default('0')->comment('0=>pending;1=>completed');
            $table->string('payment_trasfer_reciept',1000)->nullable()->default('null');
            $table->float('return_percentage');
            $table->float('investment_amount');
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
        Schema::dropIfExists('roi');
    }
}
