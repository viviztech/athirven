<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pen_name');
            $table->text('real_name')->nullable();
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->json('social_links')->nullable();
            $table->boolean('is_pseudonymous')->default(false);
            $table->text('contact_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
