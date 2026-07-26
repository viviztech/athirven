<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sponsor_name');
            $table->string('title');
            $table->string('target_url');
            $table->string('placement'); // homepage_banner|sidebar|issue_sponsor|newsletter
            $table->string('status')->default('pending'); // pending|approved|live|expired|rejected
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('price_paid')->nullable(); // minor units, informational only
            $table->string('currency', 3)->nullable();
            $table->timestamps();

            $table->index(['placement', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
