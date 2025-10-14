<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class TaskNotFoundException extends Exception
{
    protected $message = 'Tarefa não encontrada.';
    protected $code = 404;

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Task Not Found',
            'message' => $this->message,
        ], $this->code);
    }
}

