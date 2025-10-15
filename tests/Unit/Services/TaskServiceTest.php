<?php

namespace Tests\Unit\Services;

use App\Services\TaskService;
use App\Repositories\TaskRepository;
use App\Models\Task;
use Mockery;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TaskServiceTest extends TestCase
{
    private $taskRepository;
    private $taskService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->taskRepository = Mockery::mock(TaskRepository::class);
        $this->taskService = new TaskService($this->taskRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function should_get_all_tasks()
    {
        $tasks = collect([
            ['id' => 1, 'title' => 'Task 1', 'description' => 'Description 1', 'status' => 0],
            ['id' => 2, 'title' => 'Task 2', 'description' => 'Description 2', 'status' => 1],
        ]);

        $this->taskRepository
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($tasks);

        $result = $this->taskService->getAll();

        $this->assertEquals($tasks, $result);
    }

    #[Test]
    public function should_find_task_by_id()
    {
        $taskId = 1;
        $task = new Task([
            'id' => $taskId,
            'title' => 'My Task',
            'description' => 'Task description',
            'status' => 0
        ]);

        $this->taskRepository
            ->shouldReceive('findById')
            ->once()
            ->with($taskId)
            ->andReturn($task);

        $result = $this->taskService->findById($taskId);

        $this->assertEquals($task, $result);
    }

    #[Test]
    public function should_create_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'New task description',
            'status' => 0
        ];

        $createdTask = new Task(array_merge($taskData, ['id' => 1]));

        $this->taskRepository
            ->shouldReceive('create')
            ->once()
            ->with($taskData)
            ->andReturn($createdTask);

        $result = $this->taskService->create($taskData);

        $this->assertEquals($createdTask, $result);
    }

    #[Test]
    public function should_update_task()
    {
        $taskId = 1;
        $updateData = [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'status' => 1
        ];

        $updatedTask = new Task(array_merge($updateData, ['id' => $taskId]));

        $this->taskRepository
            ->shouldReceive('update')
            ->once()
            ->with($taskId, $updateData)
            ->andReturn($updatedTask);

        $result = $this->taskService->update($taskId, $updateData);

        $this->assertEquals($updatedTask, $result);
    }

    #[Test]
    public function should_delete_task()
    {
        $taskId = 1;

        $this->taskRepository
            ->shouldReceive('delete')
            ->once()
            ->with($taskId)
            ->andReturn(true);

        $result = $this->taskService->delete($taskId);

        $this->assertTrue($result);
    }
}
