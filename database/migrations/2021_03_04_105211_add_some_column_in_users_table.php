<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('role_id')->after('company_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('phone', 15)->unique()->after('email');
            $table->string('avatar', 80)->nullable()->after('phone');
            $table->tinyInteger('status')->default(0)->comment('0: disable, 1:enable')->after('avatar');
            $table->integer('created_by')->default(0)->after('status');
            $table->integer('updated_by')->default(0)->after('created_by');
            $table->integer('approved_by')->default(0)->after('updated_by');
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
