<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $task->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3">
                <p><strong>Description:</strong> {{ $task->description ?? '-' }}</p>
                <p><strong>Assignee:</strong> {{ $task->assignee->name }}</p>
                <p><strong>Status:</strong> <x-status-badge :status="$task->status" /></p>
                <p><strong>Due date:</strong> {{ $task->due_date?->format('Y-m-d') ?? '-' }}</p>

                @if ($task->manager_note)
                    <p class="p-3 bg-red-50 text-red-800 rounded">
                        <strong>Manager note:</strong> {{ $task->manager_note }}
                    </p>
                @endif

                <div class="pt-4 flex flex-wrap gap-4 items-start">
                    @can('complete', $task)
                        <form method="POST" action="{{ route('tasks.complete', $task) }}">
                            @csrf @method('PATCH')
                            <x-primary-button>Mark as complete</x-primary-button>
                        </form>
                    @endcan

                    @can('review', $task)
                        <form method="POST" action="{{ route('tasks.approve', $task) }}">
                            @csrf @method('PATCH')
                            <x-primary-button>Approve</x-primary-button>
                        </form>

                        <form method="POST" action="{{ route('tasks.reject', $task) }}">
                            @csrf @method('PATCH')
                            <x-input-label for="manager_note" value="Reason for rejecting" />
                            <textarea name="manager_note" id="manager_note" required
                                class="border-gray-300 rounded-md shadow-sm">{{ old('manager_note') }}</textarea>
                            <x-input-error :messages="$errors->get('manager_note')" />
                            <x-danger-button>Reject</x-danger-button>
                        </form>
                    @endcan

                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 self-center">Edit</a>
                    @endcan

                    @can('delete', $task)
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                            @csrf @method('DELETE')
                            <x-danger-button>Delete</x-danger-button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>