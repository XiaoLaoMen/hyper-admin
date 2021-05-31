<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pid')->comment('父类id');
            $table->char('name', 60)->comment('菜单名称');
            $table->char('url', 60)->comment('菜单地址');
            $table->char('icon', 60)->comment('icon图标');
            $table->integer('sort')->default(50)->comment('排序');
            $table->smallInteger('status')->default('1')->comment(' 0禁止 1显示');
            $table->smallInteger('is_default')->default('1')->comment(' 0否 1是');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
}
