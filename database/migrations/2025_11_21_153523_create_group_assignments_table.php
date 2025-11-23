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
        Schema::create('group_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups');
            $table->string('title');
            $table->foreignId('group_category_id')->constrained('group_categories');
            $table->text('description');
            $table->foreignId('priority_id')->constrained('priorities');
            $table->foreignId('group_type_assignment_id')->constrained('group_type_assignments');
            $table->dateTime('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_assignments');
    }
};
