<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido', function (Blueprint $table) {
            $table->id('pedidoid');

            // Datos del cliente externo
            $table->string('nombre_planta');

            // Cultivo (opcional)
            $table->foreignId('cultivoid')
                ->nullable()
                ->constrained('cultivo', 'cultivoid')
                ->nullOnDelete();

            // Cultivo libre si no existe en el sistema
            $table->string('cultivo_personalizado')->nullable();

            // Cantidad solicitada
            $table->foreignId('unidadmedidaid')
                ->constrained('unidadmedida', 'unidadmedidaid');

            $table->decimal('cantidad', 12, 2);

            // Ubicación de entrega (mapa)
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->string('direccion_texto')->nullable();

            // Estado del pedido
            $table->enum('estado', [
                'pendiente',
                'confirmado',
                'en produccion',
                'rechazado'
            ])->default('pendiente');

            // Fechas
            $table->timestamp('fechapedido')->useCurrent();
            $table->date('fechaEntregaDeseada')->nullable();

            // Observaciones
            $table->text('observaciones')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};