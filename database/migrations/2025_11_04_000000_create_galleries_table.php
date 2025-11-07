<?php

// This migration is a duplicate of an earlier galleries migration (2025_11_03_062732_create_galleries_table.php)
// and has been neutralized to avoid creating the same table twice when running migrations.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // no-op to prevent duplicate table creation
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // no-op
    }
};
