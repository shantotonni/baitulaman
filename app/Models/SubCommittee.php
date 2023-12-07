<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCommittee extends Model
{
    use HasFactory;

    protected $table = "sub_committee";
    protected $primaryKey = "id";
    protected $guarded = [];
}
