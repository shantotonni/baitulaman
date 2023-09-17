<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ramadan extends Model
{
    use HasFactory;
    protected $table = "ramadan_calendar";
    protected $primaryKey = "id";
    protected $guarded = [];
}
