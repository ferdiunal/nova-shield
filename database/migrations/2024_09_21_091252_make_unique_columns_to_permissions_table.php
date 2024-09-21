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
        Schema::table('permissions', function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $schemaManager->listTableIndexes('permissions');
            if (! array_key_exists('permissions_name_guard_name_unique', $doctrineTable)) {
                $table->unique(['name', 'guard_name']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $schemaManager->listTableIndexes('permissions');
            if (! array_key_exists('permissions_name_guard_name_unique', $doctrineTable)) {
                $table->dropUnique(['name', 'guard_name']);
            }
        });
    }
};
