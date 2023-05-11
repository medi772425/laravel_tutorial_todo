<?php

namespace App\Http\Requests;

use App\Task;
use Illuminate\Validation\Rule;

class EditTask extends CreateTask
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //親クラスの同名メソッドの呼び出し
        $rule = parent::rules();

        // in(1, 2, 3) の記述で、値のどれかと同じであることを検証する
        // Rule::in()を使用すると、渡した配列の変数をそのまま使用できる。
        // Task::STATUSは連想配列なので、配列に変換する必要がある
        //    array_keys(Task::STATUS)で、[1, 2, 3] と書いているのと同じ
        $status_rule = Rule::in(array_keys(Task::STATUS));

        // 親クラスのrulesと合体させたルールを返す
        return $rule + [
            'status' => 'required|' . $status_rule,
        ];
    }

    public function attributes()
    {
        $attributes = parent::attributes();

        return $attributes + [
            'status' => '状態',
        ];
    }

    public function messages()
    {
        $messages = parent::messages();

        $status_labels = array_map(function ($item) {
            return $item['label'];
        }, Task::STATUS);

        $status_labels = implode('、', $status_labels);

        return $messages + [
            'status.in' => ':attribute には ' . $status_labels . ' のいずれかを指定してください。',
        ];
    }
}
