<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('phone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->enum('department', ['Sales','HR','IT','Manager','user','Finance - accounts','Production','Designer'])->default('user');
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->string('mailid')->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('emergency_contact')->nullable();
            $table->string('blood_group')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
