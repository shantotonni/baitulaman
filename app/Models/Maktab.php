<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maktab extends Model
{
    use HasFactory;

    protected $table = "maktab";
    protected $primaryKey = "id";
    protected $guarded = [];
}
