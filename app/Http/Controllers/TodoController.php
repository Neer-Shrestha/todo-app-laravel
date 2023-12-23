<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;

class TodoController extends Controller
{
    function index()
    {
        $todos = Todo::select(['todos', 'status', 'id'])->where('user_id', '=', Auth::id())->get();
        return view('todos.index', ['todos' => $todos]);
    }
    function create(Request $request)

    {
        $request->merge([
            "user_id" => Auth::id(),
            'status' => 'pending',
        ]);
        $data = $request->validate([
            'todos' => 'required',
            'status' => 'required',
            'user_id' => 'required',
        ]);

        $new_todo = Todo::create($data);

        return redirect(route('todo.index'));
    }
    function update(Todo $todo, Request $request)
    {
        // $todo->update(['status' => 'completed']);
        $data = $request->validate([
            'todos' => 'required',
        ]);

        $todo->update($data);

        return redirect(route('todo.index'));
    }

    function status(Todo $todo, Request $request)
    {

        $data = $request->validate([
            'todo_status' => 'required',
        ]);

        $todo->update(['status' => $data['todo_status']]);

        return redirect(route('todo.index'));
    }

    function edit(Todo $todo)
    {
        return view('todos.edit', ["todo" => $todo]);
    }
    function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect(route('todo.index'));
    }
}
