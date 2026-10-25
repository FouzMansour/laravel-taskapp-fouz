<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $task->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div id="live-content" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3">
                <p><strong>{{ __('Description') }} :</strong> {{ $task->description ?? '-' }}</p>
                <p><strong>{{ __('Assignee') }}:</strong> {{ $task->assignee->name }}</p>
                <p><strong>{{ __('Status') }}:</strong>
                    <x-status-badge :status="$task->status" />
                </p>
                <p><strong>{{ __('Due date') }}:</strong> {{ $task->due_date?->format('Y-m-d') ?? '-' }}</p>
                @if ($task->manager_note)
                <p class="p-3 bg-red-50 text-red-800 rounded">
                    <strong>{{ __('Manager note') }}:</strong> {{ $task->manager_note }}
                </p>
                @endif

                <div class="pt-4 flex flex-wrap gap-4 items-start">
                    @can('complete', $task)
                    <form method="POST" action="{{ route('tasks.complete', $task) }}">
                        @csrf @method('PATCH')
                        <x-primary-button>{{ __('Mark as complete') }}</x-primary-button>
                    </form>
                    @endcan

                    @can('review', $task)
                    <form method="POST" action="{{ route('tasks.approve', $task) }}">
                        @csrf @method('PATCH')
                        <x-primary-button>{{ __('Approve') }}</x-primary-button>
                    </form>

                    <form method="POST" action="{{ route('tasks.reject', $task) }}">
                        @csrf @method('PATCH')
                        <x-input-label for="manager_note" value="Reason for rejecting" />
                        <textarea name="manager_note" id="manager_note" required
                            class="border-gray-300 rounded-md shadow-sm">{{ old('manager_note') }}</textarea>
                        <x-input-error :messages="$errors->get('manager_note')" />
                        <x-danger-button>{{ __('Reject') }}</x-danger-button>
                    </form>
                    @endcan

                    @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 self-center">{{ __('Edit') }}</a>
                    @endcan

                    @can('delete', $task)
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                        @csrf @method('DELETE')
                        <x-danger-button>{{ __('Delete') }}</x-danger-button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>