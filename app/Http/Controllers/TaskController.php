<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;

class TaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        return TaskResource::collection($this->taskService->getAll());
    }

    public function store(Request $request)
    {
        $task = $this->taskService->create($request->all());
        return new TaskResource($task);
    }

    public function update(Request $request, string $id)
    {
        $task = $this->taskService->update($id, $request->all());
        return new TaskResource($task);
    }

    public function delete(string $id)
    {
        $this->taskService->delete($id);
        return response()->json(null, 204);
    }
}
