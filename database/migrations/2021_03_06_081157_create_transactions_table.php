<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->date('transaction_date');
            $table->integer('dr_account_id');
            $table->decimal('dr_amount', 20, 2);
            $table->integer('cr_account_id');
            $table->decimal('cr_amount', 20, 2);
            $table->string('remarks', 255);
            $table->tinyInteger('status')->default(0)->comment('1:authorized, 0:un-authorized');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('approved_by')->default(0);
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
