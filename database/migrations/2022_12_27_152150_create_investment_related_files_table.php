<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentRelatedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_related_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_id')->references('id')->on('investments')->onDelete('cascade');
            $table->enum('type', ['0', '1'])->default('0')->comment('0=>payment_reciept;1=>contract_signed_file'); // 0=>payment_reciept;1=>contract_signed_file 
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('payment_reciept')->nullable();
            $table->string('signed_contract_file')->nullable();
            $table->date('terminate_date')->nullable()->default(NULL);
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
        Schema::dropIfExists('investment_related_files');
    }
}
