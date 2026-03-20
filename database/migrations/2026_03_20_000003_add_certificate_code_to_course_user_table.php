<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->string('certificate_code', 48)->nullable()->after('expires_at')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->dropUnique(['certificate_code']);
            $table->dropColumn('certificate_code');
        });
    }
};
