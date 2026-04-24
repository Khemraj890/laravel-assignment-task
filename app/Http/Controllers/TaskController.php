<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreTaskRequest;

class TaskController extends Controller
{
    public $pageUrl = "tasks";
    public function __construct() {
        $this->Models = new Task();
    }

    public function index(Request $request)
    {
        $status = $request->query('status', '');
        if (! in_array($status, ['', 'open', 'overdue'], true)) {
            $status = '';
        }
        $today = Carbon::today();
        $tasks =  $this->Models::query()
            ->when($status === 'open', function ($query) {
                $query->where('done', false);
            })
            ->when($status === 'overdue', function ($query) use ($today) {
                $query->where('done', false)
                    ->whereDate('due_date', '<', $today);
            })
            ->orderByRaw('CASE WHEN done = 1 THEN 1 ELSE 0 END ASC')
            ->orderByRaw('CASE WHEN done = 0 AND due_date IS NOT NULL THEN 0 WHEN done = 0 AND due_date IS NULL THEN 1 ELSE 2 END ASC')
            ->orderByRaw('CASE WHEN done = 0 AND due_date IS NOT NULL THEN due_date END ASC')
            ->orderByRaw('CASE WHEN done = 0 AND due_date IS NULL THEN created_at END DESC')
            ->orderByRaw('CASE WHEN done = 1 THEN updated_at END DESC')
            ->get();
        return view($this->pageUrl.'.index', [
            'tasks' => $tasks,
            'status' => $status,
            'pageUrl' => $this->pageUrl,
        ]);
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $task = $this->Models::create($validated);
        return redirect('/')
            ->with('created_task_id', $task->id)
            ->with('success_added', 'Task added successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['', 'open', 'overdue'])],
        ]);
        $task->update([
            'done' => true,
        ]);
        return redirect()
            ->route($this->pageUrl.'.index', ['status' => $validated['status'] ?? ''])
            ->with('status_updated', 'Task status updated successfully.');
    }
}
