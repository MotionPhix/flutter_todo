<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('task_lists', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->integer('position')->default(0);
      $table->timestamps();
      $table->softDeletes();
    });

    // Add list_id to tasks table
    Schema::table('tasks', function (Blueprint $table) {
      $table->foreignId('list_id')->nullable()->constrained('task_lists')->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('tasks', function (Blueprint $table) {
      $table->dropForeign(['list_id']);
      $table->dropColumn('list_id');
    });

    Schema::dropIfExists('task_lists');
  }
};
