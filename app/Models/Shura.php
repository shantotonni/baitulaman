<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shura extends Model
{
    use HasFactory;
    protected $table = "shura_committee";
    protected $primaryKey = "id";
    protected $guarded = [];
}
