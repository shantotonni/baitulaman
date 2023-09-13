<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imam extends Model
{
    use HasFactory;
    protected $table = "our_imam";
    protected $primaryKey = "id";
    protected $guarded = [];
}
