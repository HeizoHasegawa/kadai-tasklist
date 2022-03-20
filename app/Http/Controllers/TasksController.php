<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            // タスク一覧を取得
            $tasks = Task::where("user_id", "=", $user->id)->get();
            $data = [
                "user" => $user,
                "tasks" => $tasks,
            ];

        }
        // メッセージ一覧ビューでそれを表示
            
        return view('welcome', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()) {
            $task = new Task;

        // タスク作成ビューを表示
            return view('tasks.create', ['task' => $task, ]);
        }
        return redirect("/");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        // タスクを作成
        $request->user()->tasks()->create([
            "content" => $request->content,
            "status" => $request->status,
        ]);

        // トップページへリダイレクトさせる
        return redirect("/");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            // タスク一覧を取得
            $task = Task::findOrFail($id);
            // ログイン中のユーザのタスクであることを確認
            if ($user->id == $task->user_id) {
                return view('tasks.show', [ 'task' => $task, ]);
            }
        }
        return redirect("/");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            // タスク一覧を取得
            $task = Task::findOrFail($id);
            // ログイン中のユーザのタスクであることを確認
            if ($user->id == $task->user_id) {
                return view('tasks.edit', [ 'task' => $task, ]);
            }
        }
        return redirect("/");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
