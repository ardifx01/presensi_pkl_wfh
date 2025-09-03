<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','temp_password')) {
                $table->string('temp_password')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users','force_password_change')) {
                $table->boolean('force_password_change')->default(false)->after('temp_password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','temp_password')) {
                $table->dropColumn('temp_password');
            }
            if (Schema::hasColumn('users','force_password_change')) {
                $table->dropColumn('force_password_change');
            }
        });
    }
};
