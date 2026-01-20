<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina la restricción única global del slug y la reemplaza
     * por una restricción única compuesta (slug + module_id).
     * Esto permite que diferentes módulos tengan lecciones con el mismo slug.
     */
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Eliminar la restricción única global del slug
            $table->dropUnique(['slug']);
            
            // Agregar restricción única compuesta: slug debe ser único dentro de cada módulo
            $table->unique(['slug', 'module_id'], 'lessons_slug_module_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Eliminar la restricción única compuesta
            $table->dropUnique('lessons_slug_module_unique');
            
            // Restaurar la restricción única global del slug
            $table->unique('slug');
        });
    }
};
