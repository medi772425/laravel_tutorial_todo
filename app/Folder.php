<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    public function tasks()
    {
        // FolderテーブルとTaskテーブルが1対多の関連性(リレーション)を持っていることを記述
        // 第２引数と第３引数は省略されている。テーブル名単数形_id, id の場合は省略可能
        return $this->hasMany('App\Task');
    }
}
