<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Textdb extends Model
{
    use HasFactory;
    protected $table ="text_db";
    public $timestamps = false;
    protected $fillable = [
        'text'
    ];
}
