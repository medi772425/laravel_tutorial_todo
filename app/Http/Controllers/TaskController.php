<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tag;

class TaskController extends Controller
{
    // ルートモデルバインディング を使わずに404を返すパターン
    // // // public function index(int $id)
    public function index(Folder $folder)
    {
        // ポリシークラスを使用せずに、自分のfolder以外にアクセスした場合
        // // // if (Auth::user()->id !== $folder->user_id) {
        // // //     abort(403);
        // // // }

        // ログインユーザが持つフォルダのみを取得
        $folders = Auth::user()->folders()->get();
        $tasks = $folder->tasks()->get();
        // // // $folders = Folder::all();

        // ルートモデルバインディング を使わずに404を返すパターン
        // ------------------------------------------------------
        // // // $current_folder = Folder::find($id);
        // // // if (is_null($current_folder)) {
        // // //     abort(404);
        // // // }

        // // // // // $tasks = Task::where('folder_id', $current_folder->id)->get();
        // // // Folderモデルでリレーションしたため呼び出しできる
        // // $tasks = $current_folder->tasks()->get();
        // ------------------------------------------------------

        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $folder->id,
            'tasks' => $tasks,
        ]);
    }

    // public function showCreateForm(int $id)
    public function showCreateForm(Folder $folder)
    {
        return view('tasks/create', [
            'folder_id' => $folder->id
        ]);
    }

    // public function create(int $id, CreateTask $request)
    public function create(Folder $folder, CreateTask $request)
    {
        // $current_folder = Folder::find($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $folder->tasks()->save($task);
        // $current_folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            // 'id' => $current_folder->id,
            'id' => $folder->id,
        ]);
    }

    public function showEditForm(Folder $folder, Task $task)
    {
        $this->checkRelation($folder, $task);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(Folder $folder, Task $task, EditTask $request)
    {
        $this->checkRelation($folder, $task);

        $task->title = $request->title;
        $task->due_date = $request->due_date;
        $task->status = $request->status;

        $task->save();

        return redirect()->route('tasks.index', [
            // 'id' => $task->folder->id,
            'folder' => $folder,
        ]);
    }

    public function checkRelation(Folder $folder, Task $task)
    {
        if ($folder->id !== $task->folder_id) {
            abort(404);
        }
    }
}
