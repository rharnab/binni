<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePredefinedMappingGlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predefined_mapping_gls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->string('transaction_type', 30);
            $table->string('remarks', 255)->nullable();
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
        Schema::dropIfExists('predefined_mapping_gls');
    }
}
