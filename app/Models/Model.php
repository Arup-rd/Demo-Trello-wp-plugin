<?php

namespace MyPlugin\Models;

use MyPlugin\Framework\Database\Orm\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = ['id', 'ID'];
}
