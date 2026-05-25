<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Craft extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'image'];

    public function masterClasses(): HasMany
    {
        return $this->hasMany(MasterClass::class);
    }
}