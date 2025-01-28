<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    // Parent relationship
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Children relationship
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
