<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAdminSetsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_sets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('key')->comment('key值');
            $table->char('desc', 60)->comment('描述');
            $table->text('val', 255)->comment('值');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_sets');
    }
}
