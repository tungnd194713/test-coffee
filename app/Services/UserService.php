<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UserService implements UserServiceInterface
{
    public function updateUser($request, $model) {
        $model->fill($request->all());
        $model->save();
        DB::commit();
    }
}
