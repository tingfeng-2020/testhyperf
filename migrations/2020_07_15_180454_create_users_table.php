<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('username', 50)->comment('用户名');
            $table->string('password', 100)->comment('密码');
            $table->string('nick_name', 50)->comment('昵称');
            $table->string('real_name', 10)->comment('真实名称');
            $table->tinyInteger('sex')->default(0)->comment('0无性别 1男 2女');
            $table->string('phone', 15)->default('')->comment('手机');
            $table->string('avatar', 100)->default('')->comment('头像');
            $table->dateTimeTz('last_login_at')->default(\Hyperf\DbConnection\Db::raw('CURRENT_TIMESTAMP'))->comment('最后一次登陆时间');
            $table->dateTimeTz('created_at')->default(\Hyperf\DbConnection\Db::raw('CURRENT_TIMESTAMP'));
            $table->dateTimeTz('updated_at')->default(\Hyperf\DbConnection\Db::raw('CURRENT_TIMESTAMP'));
            $table->string('remember_token', 100)->default('');
            $table->tinyInteger('status')->default(1)->comment('0为禁用，1为正常');
            //
            $table->unique('username');
            $table->unique('phone');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
