<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display tasks, optionally filtered by project.
     */
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();

        $selectedProjectId = $request->integer('project_id');

        $selectedProject = $selectedProjectId
            ? Project::find($selectedProjectId)
            : null;

        $tasks = $selectedProject
            ? $selectedProject->tasks()
                ->orderBy('priority')
                ->get()
            : collect();

        return view('tasks.index', compact(
            'projects',
            'selectedProject',
            'tasks'
        ));
    }

    /**
     * Show the form for creating a task.
     */
    public function create(Request $request): View
    {
        $projects = Project::orderBy('name')->get();

        $selectedProjectId = $request->integer('project_id');

        return view('tasks.create', compact(
            'projects',
            'selectedProjectId'
        ));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => [
                'required',
                'integer',
                Rule::exists('projects', 'id'),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $nextPriority = Task::where(
            'project_id',
            $validated['project_id']
        )->max('priority');

        Task::create([
            'project_id' => $validated['project_id'],
            'name' => $validated['name'],
            'priority' => ($nextPriority ?? 0) + 1,
        ]);

        return redirect()
            ->route('tasks.index', [
                'project_id' => $validated['project_id'],
            ])
            ->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing a task.
     */
    public function edit(Task $task): View
    {
        $projects = Project::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'projects'));
    }

    /**
     * Update the specified task.
     */
    public function update(
        Request $request,
        Task $task
    ): RedirectResponse {
        $validated = $request->validate([
            'project_id' => [
                'required',
                'integer',
                Rule::exists('projects', 'id'),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $oldProjectId = $task->project_id;
        $newProjectId = (int) $validated['project_id'];

        DB::transaction(function () use (
            $task,
            $validated,
            $oldProjectId,
            $newProjectId
        ) {
            $task->update([
                'project_id' => $newProjectId,
                'name' => $validated['name'],
            ]);

            /*
             * If the task was moved to another project,
             * place it at the bottom of the new project's list.
             */
            if ($oldProjectId !== $newProjectId) {
                $task->priority = (
                    Task::where('project_id', $newProjectId)
                        ->where('id', '!=', $task->id)
                        ->max('priority') ?? 0
                ) + 1;

                $task->save();

                $this->normalizePriorities($oldProjectId);
                $this->normalizePriorities($newProjectId);
            }
        });

        return redirect()
            ->route('tasks.index', [
                'project_id' => $task->project_id,
            ])
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;

        DB::transaction(function () use ($task, $projectId) {
            $task->delete();

            $this->normalizePriorities($projectId);
        });

        return redirect()
            ->route('tasks.index', [
                'project_id' => $projectId,
            ])
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Update task priorities after drag and drop.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id' => [
                'required',
                'integer',
                Rule::exists('projects', 'id'),
            ],
            'tasks' => [
                'required',
                'array',
            ],
            'tasks.*' => [
                'integer',
                Rule::exists('tasks', 'id'),
            ],
        ]);

        $projectId = (int) $validated['project_id'];
        $taskIds = $validated['tasks'];

        DB::transaction(function () use ($projectId, $taskIds) {
            foreach ($taskIds as $index => $taskId) {
                Task::where('id', $taskId)
                    ->where('project_id', $projectId)
                    ->update([
                        'priority' => $index + 1,
                    ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Task priorities updated successfully.',
        ]);
    }

    /**
     * Normalize priorities to 1, 2, 3...
     */
    private function normalizePriorities(int $projectId): void
    {
        $tasks = Task::where('project_id', $projectId)
            ->orderBy('priority')
            ->orderBy('id')
            ->get();

        foreach ($tasks as $index => $task) {
            $task->update([
                'priority' => $index + 1,
            ]);
        }
    }
}