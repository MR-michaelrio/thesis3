<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id_user'); // Primary key
            $table->string('email')->unique();
            $table->text('password');
            $table->integer('id_department')->nullable();
            $table->integer('id_department_position')->nullable();
            $table->string('supervisor')->nullable();
            $table->string('start_work')->nullable();
            $table->string('stop_work')->nullable();
            $table->string('role')->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_name')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->integer('id_company')->nullable();
            $table->timestamps(); // created_at and updated_at
            $table->string('name')->nullable();

            // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
