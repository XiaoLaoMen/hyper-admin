<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name', 60)->default('')->comment('用户名');
            $table->char('email', 60)->comment('邮箱');
            $table->char('password', 60)->comment('密码');
            $table->char('role_id')->default('')->comment('角色id');
            $table->smallInteger('status')->default('1')->comment('状态 0禁止登录 1允许登录');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
}
