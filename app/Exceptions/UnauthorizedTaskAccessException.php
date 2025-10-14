<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class UnauthorizedTaskAccessException extends Exception
{
    protected $message = 'Você não tem permissão para acessar esta tarefa.';
    protected $code = 403;

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Unauthorized Access',
            'message' => $this->message,
        ], $this->code);
    }
}

