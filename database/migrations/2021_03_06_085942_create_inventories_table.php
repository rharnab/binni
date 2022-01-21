<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('name', 80);
            $table->string('image', 80)->nullable();
            $table->string('short_description', 255);
            $table->text('description');
            $table->tinyInteger('asset_type')->default(0)->comment('0:office accessories, 1: fixed asset');
            $table->float('depreciation_value')->default(0.00);
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
        Schema::dropIfExists('inventories');
    }
}
