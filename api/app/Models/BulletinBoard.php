<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulletinBoard extends BaseModel
{
    protected $primaryKey = 'board_id';

    protected $fillable = [
        'title', 'category_id', 'price', 'description',
    ];

}
