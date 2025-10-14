<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class TaskOperationException extends Exception
{
    protected $code = 500;

    public function __construct(string $message = 'Erro ao executar operação na tarefa.', int $code = 500)
    {
        parent::__construct($message, $code);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Task Operation Failed',
            'message' => $this->message,
        ], $this->code);
    }
}

