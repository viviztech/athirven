<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name_ta');
            $table->string('name_en');
            $table->string('tier'); // digital|print_digital|patron
            $table->string('gateway'); // stripe|razorpay
            $table->string('interval'); // month|year
            $table->unsignedInteger('amount'); // minor units (cents/paise)
            $table->string('currency', 3);
            $table->string('stripe_price_id')->nullable();
            $table->string('razorpay_plan_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
