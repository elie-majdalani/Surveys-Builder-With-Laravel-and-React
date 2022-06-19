<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    public function Option()
    {
        return $this->hasMany(Option::class);
    }
    public $timestamps = false;
    protected $fillable = [
        'name',
        'question',
        'type',
    ];
}