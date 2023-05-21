<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToFolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // table() すでに存在するfoldersテーブルを更新する
        // user_idカラムをfoldersテーブルに追加
        Schema::table('folders', function (Blueprint $table) {
            // unsigned() 整数カラムを符号なしに設定
            $table->integer('user_id')->unsigned();

            // 外部キー
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
