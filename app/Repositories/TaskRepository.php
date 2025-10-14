<?php

namespace App\Repositories;

use App\Models\Task;
use App\Exceptions\TaskNotFoundException;
use App\Exceptions\TaskOperationException;

class TaskRepository
{
    public function getAll()
    {
        try {
            return Task::all();
        } catch (\Exception $e) {
            throw new TaskOperationException('Erro ao buscar tarefas: ' . $e->getMessage());
        }
    }

    public function findById(int $id)
    {
        $task = Task::find($id);

        if (!$task) {
            throw new TaskNotFoundException("Tarefa com ID {$id} não encontrada.");
        }

        return $task;
    }

    public function create(array $data)
    {
        try {
            return Task::create($data);
        } catch (\Exception $e) {
            throw new TaskOperationException('Erro ao criar tarefa: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data)
    {
        $task = $this->findById($id);

        try {
            $task->update($data);
            return $task->fresh();
        } catch (\Exception $e) {
            throw new TaskOperationException('Erro ao atualizar tarefa: ' . $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        $task = $this->findById($id);

        try {
            return $task->delete();
        } catch (\Exception $e) {
            throw new TaskOperationException('Erro ao deletar tarefa: ' . $e->getMessage());
        }
    }
}