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
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->integer('semester_number');
            $table->foreignId('year_id')->constrained('years')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('trimesters', function (Blueprint $table) {
            $table->id();
            $table->integer('trimester_number');
            $table->foreignId('year_id')->constrained('years')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('teachings', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('apogee_code', 10);
            $table->decimal('tp_hours_initial', 5, 2);
            $table->decimal('tp_hours_continued', 5, 2)->nullable();
            $table->decimal('td_hours_initial', 5, 2);
            $table->decimal('td_hours_continued', 5, 2)->nullable();
            $table->decimal('cm_hours_initial', 5, 2);
            $table->decimal('cm_hours_continued', 5, 2)->nullable();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('cascade');
            $table->foreignId('trimester_id')->nullable()->constrained('trimesters')->onDelete('cascade');
            $table->foreignId('year_id')->constrained('years')->onDelete('cascade');
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachings');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('trimesters');
    }
};
