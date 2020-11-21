<?php

namespace MyPlugin\Hooks\Handlers;

class Exception
{
    protected $handlers = [
        'MyPlugin\Framework\Foundation\ForbiddenException'       => 'handleForbiddenException',
        'MyPlugin\Framework\Validator\ValidationException'       => 'handleValidationException',
        'MyPlugin\Framework\Foundation\UnAuthorizedException'    => 'handleUnAuthorizedException',
        'MyPlugin\Framework\Database\Orm\ModelNotFoundException' => 'handleModelNotFoundException',
    ];

    public function handle($e)
    {
        foreach ($this->handlers as $key => $value) {
            if ($e instanceof $key) {
                return $this->{$value}($e);
            }
        }
    }

    public function handleModelNotFoundException($e)
    {
        $this->sendError([
            'message' => $e->getMessage()
        ], $e->getCode() ?: 404);
    }

    public function handleUnAuthorizedException($e)
    {
        $this->sendError([
            'message' => $e->getMessage()
        ], $e->getCode() ?: 401);
    }

    public function handleForbiddenException($e)
    {
        $this->sendError([
            'message' => $e->getMessage()
        ], $e->getCode() ?: 403);
    }

    public function handleValidationException($e)
    {
        $this->sendError([
            'message' => $e->getMessage(),
            'errors'  => $e->errors()
        ], $e->getCode() ?: 422);
    }
}
