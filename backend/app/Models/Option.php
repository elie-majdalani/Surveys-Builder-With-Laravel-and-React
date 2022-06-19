<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    public function Question()
    {
        return $this->belongsTo(Question::class);
    }
    public $timestamps = false;
    protected $fillable = [
        'question_id',
        'option',
        'answer',
    ];
}
