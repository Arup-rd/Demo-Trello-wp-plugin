<?php

namespace MyPlugin\Http\Requests;

use MyPlugin\Framework\Foundation\RequestGuard;

abstract class Request extends RequestGuard
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }
}
