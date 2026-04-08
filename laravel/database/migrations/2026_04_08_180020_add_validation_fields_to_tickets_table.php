<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (! Schema::hasColumn('tickets', 'validation_comment')) {
                $table->text('validation_comment')->nullable()->after('collaborators');
            }

            if (! Schema::hasColumn('tickets', 'validated_at')) {
                $table->timestamp('validated_at')->nullable()->after('validation_comment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'validated_at')) {
                $table->dropColumn('validated_at');
            }

            if (Schema::hasColumn('tickets', 'validation_comment')) {
                $table->dropColumn('validation_comment');
            }
        });
    }
};
