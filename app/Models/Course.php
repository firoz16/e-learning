<?php

namespace App\Models;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['title', 'description', 'thumbnail', 'price', 'materials'];
    protected $casts = ['materials' => 'array'];

    public function enrollments() 
    { 
        return $this->hasMany(Enrollment::class); 
    }
}
