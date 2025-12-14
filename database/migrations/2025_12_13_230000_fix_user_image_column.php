<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Tabla usuario
        if (Schema::hasTable('usuario')) {
            DB::statement('ALTER TABLE usuario ALTER COLUMN imagenurl TYPE TEXT');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('usuario')) {
            DB::statement('ALTER TABLE usuario ALTER COLUMN imagenurl TYPE VARCHAR(255)');
        }
    }
};
