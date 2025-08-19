<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ModelValidator
{
    public function validate(Model $model);
}
