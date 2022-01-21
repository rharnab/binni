<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPredefinedGlIdInPredefinedMappingGlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('predefined_mapping_gls', function (Blueprint $table) {
            $table->unsignedBigInteger('predefined_gl_id')->after('company_id');
            $table->foreign('predefined_gl_id')->references('id')->on('predefined_gls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('predefined_mapping_gls', function (Blueprint $table) {
            //
        });
    }
}
