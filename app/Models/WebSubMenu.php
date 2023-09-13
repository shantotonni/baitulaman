<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSubMenu extends Model
{
    use HasFactory;
    protected $table = "web_sub_menu";
    protected $primaryKey = "id";
    protected $guarded = [];
    public function Webmenu()
    {
        return $this->belongsTo(WebMenu::class, 'menu_id', 'id');
    }
}
