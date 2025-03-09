<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('timezone')->default('UTC')->after('email');
      $table->json('preferences')->nullable()->after('timezone');

      // Add soft deletes
      $table->softDeletes();
    });
  }

  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['timezone', 'preferences']);
      $table->dropSoftDeletes();
    });
  }
};
