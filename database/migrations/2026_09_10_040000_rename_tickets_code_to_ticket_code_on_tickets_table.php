<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('tickets', 'tickets_code')) {
            DB::statement('ALTER TABLE `tickets` CHANGE `tickets_code` `ticket_code` VARCHAR(255) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('tickets', 'ticket_code')) {
            DB::statement('ALTER TABLE `tickets` CHANGE `ticket_code` `tickets_code` VARCHAR(255) NOT NULL');
        }
    }
};
