<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branding_settings', function (Blueprint $table): void {
            $table->string('dark_logo_path')->nullable()->after('logo_path');
            $table->string('favicon_path')->nullable()->after('certificate_background_path');
        });
    }

    public function down(): void
    {
        Schema::table('branding_settings', function (Blueprint $table): void {
            $table->dropColumn(['dark_logo_path', 'favicon_path']);
        });
    }
};

