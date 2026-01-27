<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Permite que la portada del curso sea imagen (actual) o video (iframe embebido
     * de Bunny, YouTube, etc.), similar al iframe_code de las lecciones.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('cover_type', 20)->default('image')->after('image_url');
            $table->text('cover_video_embed')->nullable()->after('cover_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['cover_type', 'cover_video_embed']);
        });
    }
};
