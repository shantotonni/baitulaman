<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebMenu extends Model
{
    use HasFactory;
    protected $table = "web_menu";
    protected $primaryKey = "id";
    protected $guarded = [];
}
