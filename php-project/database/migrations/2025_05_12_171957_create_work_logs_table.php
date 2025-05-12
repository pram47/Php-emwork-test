<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('job_type', ['Development', 'Test', 'Document']);
            $table->string('job_name');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['ดำเนินการ', 'เสร็จสิ้น', 'ยกเลิก']);
            $table->date('work_date');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};
