<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->unsignedBigInteger('designation_id');
            $table->foreign('designation_id')->references('id')->on('designations');
            $table->unsignedBigInteger('blood_group_id');
            $table->foreign('blood_group_id')->references('id')->on('blood_groups');
            $table->string('name', 80);
            $table->string('father_name', 80);
            $table->string('mother_name', 80);
            $table->tinyInteger('is_merried')->default(0)->comment('0:unmarried, 1: married');
            $table->string('spouse_name',80)->nullable();
            $table->string('personal_phone',15);
            $table->string('official_phone',15)->nullable();
            $table->text('current_address');
            $table->text('permanent_address');
            $table->text('reference')->nullable();
            $table->string('national_id_no', 20)->nullable();
            $table->string('passport_id_no', 20)->nullable();
            $table->string('emergency_contact_person', 80)->nullable();
            $table->string('emergency_contact_person_relation', 80)->nullable();
            $table->string('emergency_contact_number', 15)->nullable();
            $table->float('previous_working_experience')->default(0.00);
            $table->date('join_date');
            $table->tinyInteger('work_type')->comment('0:parttime, 1:fulltime, 2:contactual');
            $table->string('resume', 80)->nullable();
            $table->string('photo', 80)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:un-authorized, 1:authorized');
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
        Schema::dropIfExists('employees');
    }
}
