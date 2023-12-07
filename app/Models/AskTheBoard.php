<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AskTheBoard extends Model
{
    use HasFactory;

    protected $table = "ask_board";
    protected $primaryKey = "id";
    protected $guarded = [];
}
