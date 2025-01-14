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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->decimal('duration', 3, 1);
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->foreignId('teaching_id')->constrained('teachings');
            $table->foreignId('substitute_teacher_id')->nullable()->constrained('teachers');
            $table->foreignId('academic_promotion_id')
                ->nullable()
                ->default(null)
                ->constrained('academic_promotions')
                ->onDelete('cascade');
                
            $table->foreignId('academic_group_id')
                ->nullable()
                ->default(null)
                ->constrained('academic_groups');

            $table->foreignId('academic_subgroup_id')
                ->nullable()
                ->default(null)
                ->constrained('academic_subgroups');

            $table->boolean('is_neutralized')->default(false);
            $table->foreignId('week_id')->constrained('weeks');
            $table->enum('type', ['CM', 'TD', 'TP']); // Ajout du type
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
