<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 100);
            $table->text('description')->nullable();

            $table->foreignId('category_id')->constrained('personal_categories')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('personal_types')->onDelete('cascade');
            $table->foreignId('priority_id')->constrained('priorities');
            $table->foreignId('status_id')->constrained('statuses');

            $table->dateTime('due_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_assignments');
    }
};
