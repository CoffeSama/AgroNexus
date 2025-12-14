<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Modificar columna a TEXT para soportar Base64 (sin limite de longitud)
        // Usamos SQL nativo porque doctrine/dbal no está instalado

        // Tabla lote
        if (Schema::hasTable('lote')) {
            DB::statement('ALTER TABLE lote ALTER COLUMN imagenurl TYPE TEXT');
        }

        // Tabla produccion (preventivo)
        if (Schema::hasTable('produccion')) {
            DB::statement('ALTER TABLE produccion ALTER COLUMN imagenurl TYPE TEXT');
        }
    }

    public function down(): void
    {
        // Revertir a VARCHAR(255) - Cuidado: esto truncará los datos Base64
        if (Schema::hasTable('lote')) {
            DB::statement('ALTER TABLE lote ALTER COLUMN imagenurl TYPE VARCHAR(255)');
        }

        if (Schema::hasTable('produccion')) {
            DB::statement('ALTER TABLE produccion ALTER COLUMN imagenurl TYPE VARCHAR(255)');
        }
    }
};
