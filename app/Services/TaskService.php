<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Exceptions\InvalidTaskDataException;

class TaskService
{
    private $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAll()
    {
        return $this->taskRepository->getAll();
    }

    public function findById(int $id)
    {
        return $this->taskRepository->findById($id);
    }

    public function create(array $data)
    {
        $this->validateTaskData($data);
        return $this->taskRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $this->validateTaskData($data, false);
        return $this->taskRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->taskRepository->delete($id);
    }

    private function validateTaskData(array $data, bool $isCreating = true): void
    {
        if ($isCreating) {
            if (empty($data['title'])) {
                throw new InvalidTaskDataException('O título da tarefa é obrigatório.');
            }
        }

        if (isset($data['title']) && strlen($data['title']) > 255) {
            throw new InvalidTaskDataException('O título da tarefa não pode ter mais de 255 caracteres.');
        }

        if (isset($data['status']) && !in_array($data['status'], [0, 1])) {
            throw new InvalidTaskDataException('Status inválido. Use: 0 ou 1.');
        }
    }
}