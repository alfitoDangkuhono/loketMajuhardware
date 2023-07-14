<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class table_no_antrian extends Model
{
    use HasFactory;


    protected $table ='table_no_antrian';
    protected $primaryKey ='id';
    protected $fillable = [
        'id',
        'no_antrian',
        'huruf',
        'created_at',
        'tgl',
        'waktu',
        'jenis',
        'st',
        'cntr'
    ];
}
