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
        // MariaDB 10.4 tidak mendukung RENAME COLUMN, jadi pakai CHANGE
        // (doctrine/dbal tidak terinstal sehingga renameColumn() tidak bisa dipakai).
        DB::statement('ALTER TABLE `tickets` CHANGE `tickets_code` `ticket_code` VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `tickets` CHANGE `ticket_code` `tickets_code` VARCHAR(255) NOT NULL');
    }
};
