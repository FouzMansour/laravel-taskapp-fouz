<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\RejectTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class TaskStatusController extends Controller
{
    public function complete(Task $task)
    {
        Gate::authorize('complete', $task);

        $task->update([
            'status' => TaskStatus::Completed,
            'manager_note' => null,
        ]);

        return redirect()->route('tasks.show', $task)->with('status', 'Task updated.');
    }

    public function approve(Task $task)
    {
        Gate::authorize('review', $task);

        $task->update(['status' => TaskStatus::Approved]);

        return redirect()->route('tasks.show', $task)->with('status', 'Task updated.');
    }

    public function reject(RejectTaskRequest $request, Task $task)
    {
        Gate::authorize('review', $task);

        $task->update([
            'status' => TaskStatus::Rejected,
            'manager_note' => $request->validated()['manager_note'],
        ]);

        return redirect()->route('tasks.show', $task)->with('status', 'Task updated.');
    }
}
