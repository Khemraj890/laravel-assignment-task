@extends('layout')

@section('content')
    <div class="card w-full">
        @if (session('success_added'))
            <div
                class="mb-4 flex items-start gap-2 rounded-md border border-green-200 bg-green-50 px-3 py-2.5 text-sm text-green-900 dark:border-green-800/80 dark:bg-green-950/40 dark:text-green-100"
                role="status"
                aria-live="polite"
            >
                <span class="mt-0.5 text-green-600 dark:text-green-400" aria-hidden="true">✓</span>
                <span class="leading-snug">{{ session('success_added') }}</span>
            </div>
        @endif

        @if (session('status_updated'))
            <div
                class="mb-4 flex items-start gap-2 rounded-md border border-blue-200 bg-blue-50 px-3 py-2.5 text-sm text-blue-900 dark:border-blue-800/80 dark:bg-blue-950/40 dark:text-blue-100"
                role="status"
                aria-live="polite"
            >
                <span class="mt-0.5 text-blue-600 dark:text-blue-400" aria-hidden="true">ℹ</span>
                <span class="leading-snug">{{ session('status_updated') }}</span>
            </div>
        @endif

        <section>
            <form class="form grid gap-6" method="POST" action="{{ route($pageUrl.'.store') }}">
                @csrf

                <div class="grid gap-2">
                    <label for="task_description">Description</label>
                    <div class="grid grid-cols-[1fr_180px] gap-2">
                        <input
                            type="text"
                            id="task_description"
                            name="description"
                            placeholder="describe the task..."
                            value="{{ old('description') }}"
                            tabindex="1"
                            autofocus
                        >
                        <button type="submit" class="btn" tabindex="3">Add</button>
                    </div>
                    @error('description')
                        <div
                            class="mt-1 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800 dark:border-red-800/80 dark:bg-red-950/40 dark:text-red-100"
                            role="alert"
                        >
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="grid gap-2">
                    <label for="task_due_date">Due date</label>
                    <input
                        type="date"
                        id="task_due_date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        tabindex="2"
                    >
                    @error('due_date')
                        <div
                            class="mt-1 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800 dark:border-red-800/80 dark:bg-red-950/40 dark:text-red-100"
                            role="alert"
                        >
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </form>
        </section>

        <hr>

        <section>
            <form class="form flex gap-2 mb-6" method="GET" action="{{ route($pageUrl.'.index') }}">
                <label for="filter_status">Filter tasks</label>
                <select id="filter_status" name="status">
                    <option value="" @selected($status === '')>All</option>
                    <option value="open" @selected($status === 'open')>Open</option>
                    <option value="overdue" @selected($status === 'overdue')>Overdue</option>
                </select>
                <button type="submit" class="btn">Filter</button>
            </form>

            <ul class="grid gap-4">
                @forelse ($tasks as $task)
                    <li class="flex items-center gap-4{{ session('created_task_id') === $task->id ? ' new-task-highlight' : '' }}">
                        <div class="flex flex-col gap-1 mr-auto">
                            <p class="text-sm font-medium leading-none{{ $task->done ? ' line-through' : '' }}">
                                {{ $task->description }}
                            </p>
                            @if ($task->due_date)
                                <p class="text-sm font-muted leading-none{{ $task->done ? ' line-through' : '' }}">
                                    {{ $task->due_date->format('d-m-Y') }}
                                </p>
                            @endif
                        </div>

                        @if (! $task->done)
                            <form class="form" method="POST" action="{{ route($pageUrl.'.update', $task) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $status }}">
                                <button type="submit" class="btn-sm-outline">Done</button>
                            </form>
                        @endif
                    </li>
                @empty
                    <li class="text-sm font-muted">No tasks found</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection
