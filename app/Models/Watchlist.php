<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

//    protected $hidden = ["user_id"];

    protected $casts = [
      "created_at" => 'datetime',
      "updated_at" => 'datetime',
    ];

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
      return $this->hasMany(WatchlistItem::class);
    }
}
