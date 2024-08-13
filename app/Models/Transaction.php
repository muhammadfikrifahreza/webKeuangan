<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'note',
        'date',
        'amount',
        'image'
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(category::class);
    }

    public function scopeIncomes($query)
    {
        return $query->whereHas('category', function ($query){
            $query->where('is_expense',false);
        });
    }

    public function scopeExpenses($query)
    {
        return $query->whereHas('category', function ($query){
            $query->where('is_expense',true);
        });
    }

}
