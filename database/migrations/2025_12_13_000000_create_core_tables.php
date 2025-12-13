<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabla Usuario
        if (!Schema::hasTable('usuario')) {
            Schema::create('usuario', function (Blueprint $table) {
                $table->id('usuarioid');
                $table->string('nombre');
                $table->string('apellido');
                $table->string('email')->unique();
                $table->string('nombreusuario')->unique();
                $table->string('telefono')->nullable();
                $table->string('passwordhash');
                $table->text('imagenurl')->nullable();
                $table->text('informacionadicional')->nullable();
                $table->dateTime('fecharegistro')->useCurrent();
                $table->dateTime('fechamodificacion')->useCurrent();
                $table->dateTime('ultimologin')->nullable();
                $table->boolean('activo')->default(true);
            });
        }

        // Tabla Rol (Legacy/Custom)
        if (!Schema::hasTable('rol')) {
            Schema::create('rol', function (Blueprint $table) {
                $table->id('rolid');
                $table->string('nombre');
                $table->text('descripcion')->nullable();
            });
        }

        // Tabla Pivote UsuarioRol
        if (!Schema::hasTable('usuariorol')) {
            Schema::create('usuariorol', function (Blueprint $table) {
                $table->id('usuariorolid');
                $table->unsignedBigInteger('usuarioid');
                $table->unsignedBigInteger('rolid');

                $table->foreign('usuarioid')->references('usuarioid')->on('usuario')->onDelete('cascade');
                $table->foreign('rolid')->references('rolid')->on('rol')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('usuariorol');
        Schema::dropIfExists('rol');
        Schema::dropIfExists('usuario');
    }
};
