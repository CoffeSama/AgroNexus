<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Catalogos Simples
        $catalogs = [
            'tipoinsumo' => 'tipoinsumoid',
            'unidadmedida' => 'unidadmedidaid',
            'cultivo' => 'cultivoid',
            'tipoactividad' => 'tipoactividadid',
            'tipoalmacen' => 'tipoalmacenid',
            'prioridad' => 'prioridadid',
            'estadoloteinsumo' => 'estadoloteinsumoid',
            'estadolotetipo' => 'estadolotetipoid',
            'destinoproduccion' => 'destinoproduccionid',
        ];

        foreach ($catalogs as $tableName => $pkName) {
            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) use ($pkName) {
                    $table->id($pkName);
                    $table->string('nombre');
                    $table->text('descripcion')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        $catalogs = [
            'destinoproduccion',
            'estadolotetipo',
            'estadoloteinsumo',
            'prioridad',
            'tipoalmacen',
            'tipoactividad',
            'cultivo',
            'unidadmedida',
            'tipoinsumo',
        ];

        foreach ($catalogs as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
};
