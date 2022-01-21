<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('account_type_id');
            $table->foreign('account_type_id')->references('id')->on('account_types');
            $table->string('name', 80);
            $table->string('acc_code', 6);
            $table->tinyInteger('account_level');
            $table->integer('immediate_parent');
            $table->boolean('allow_manual_transaction')->default(false);
            $table->boolean('allow_negative_balance')->default(false);
            $table->decimal('total_dr_balance', 20, 2)->default(0.00);
            $table->decimal('total_cr_balance', 20, 2)->default(0.00);
            $table->decimal('current_balance', 20, 2)->default(0.00);
            $table->tinyInteger('status')->default(0)->comment('0:disable, 1:enable');
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
        Schema::dropIfExists('accounts');
    }
}
