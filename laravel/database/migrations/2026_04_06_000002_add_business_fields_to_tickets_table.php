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
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'status')) {
                $table->string('status')->default('Nouveau')->after('description');
            }

            if (!Schema::hasColumn('tickets', 'type')) {
                $table->string('type')->default('Inclus')->after('status');
            }

            if (!Schema::hasColumn('tickets', 'priority')) {
                $table->string('priority')->nullable()->after('type');
            }

            if (!Schema::hasColumn('tickets', 'collaborators')) {
                $table->json('collaborators')->nullable()->after('priority');
            }

            if (!Schema::hasColumn('tickets', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('hours_spent')->constrained('projects')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }

            $columnsToDrop = [];

            foreach (['status', 'type', 'priority', 'collaborators'] as $column) {
                if (Schema::hasColumn('tickets', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
