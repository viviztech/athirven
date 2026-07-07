<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('created_by_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable()->after('scheduled_at');
            $table->timestamp('proofread_at')->nullable()->after('submitted_at');
            $table->foreignId('proofread_by_id')->nullable()->after('proofread_at')->constrained('users')->nullOnDelete();
            $table->text('proofreader_notes')->nullable()->after('proofread_by_id');
            $table->text('revision_notes')->nullable()->after('proofreader_notes');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by_id');
            $table->dropConstrainedForeignId('proofread_by_id');
            $table->dropColumn(['submitted_at', 'proofread_at', 'proofreader_notes', 'revision_notes']);
        });
    }
};
