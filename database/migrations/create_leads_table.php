<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('contact_info')->nullable();
            $table->string('city')->nullable();
            $table->date('upload_date')->nullable();
            $table->enum('status', [
                'Ringing','Not Answered','Contact Number Incorrect','International Number','Switched Off',
                'Already Finalized','Not Interested','Not Required','If Required Will Connect Us',
                'Interested, Shared Profile','Almost Closed','Closed','Other'
            ])->nullable();
            $table->enum('priority', ['High','Medium','Low'])->default('Medium');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('actions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
