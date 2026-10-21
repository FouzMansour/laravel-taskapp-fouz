<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class TaskUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public int $taskId,
        public int $assignedTo,
        public int $createdBy,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tasks.' . $this->assignedTo),
            new PrivateChannel('tasks.' . $this->createdBy),
        ];
    }

    public function broadcastAs(): string
    {
        return 'task.updated';
    }

    public function broadcastWith(): array
    {
        return ['task_id' => $this->taskId];
    }
}
