<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InvalidTaskDataException extends Exception
{
    protected $message = 'Dados inválidos fornecidos para a tarefa.';
    protected $code = 422;

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Invalid Task Data',
            'message' => $this->message,
        ], $this->code);
    }
}

