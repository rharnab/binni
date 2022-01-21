<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyVaultTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_vault_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('voult_account_id');
            $table->foreign('voult_account_id')->references('id')->on('accounts');
            $table->date('transaction_date');
            $table->string('transaction_type', 5);
            $table->decimal('amount', 20, 2)->default(0.00);
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
        Schema::dropIfExists('company_vault_transactions');
    }
}
