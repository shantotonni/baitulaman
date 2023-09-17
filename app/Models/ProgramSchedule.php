<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramSchedule extends Model
{
    use HasFactory;
    protected $table = "program_schedule";
    protected $primaryKey = "id";
    protected $guarded = [];
}
