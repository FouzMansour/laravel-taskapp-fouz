<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Task::class);

        $tasks = $request->user()->isManager()
            ? Task::with('assignee')->latest()->get()
            : $request->user()->tasks()->with('assignee')->latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        Gate::authorize('create', Task::class);

        $employees = User::where('role', 'employee')->get();

        return view('tasks.create', compact('employees'));
    }

    public function store(StoreTaskRequest $request)
    {
        Gate::authorize('create', Task::class);

        $task = Task::create($request->validated() + [
            'created_by' => $request->user()->id,
            'status' => TaskStatus::Pending,
        ]);

        return redirect()->route('tasks.show', $task)->with('status', 'Task created.');
    }

    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        $employees = User::where('role', 'employee')->get();

        return view('tasks.edit', compact('task', 'employees'));
    }

    public function update(StoreTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return redirect()->route('tasks.show', $task)->with('status', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'Task deleted.');
    }
}
