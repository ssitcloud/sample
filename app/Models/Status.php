<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //批量赋值
    protected $fillable = ['content'];

    //定义模型关联
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}