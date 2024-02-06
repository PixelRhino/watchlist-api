<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WatchlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
      "watchlist_id",
      "media_id",
      "name",
      "season",
      "episode",
    ];

    public function watchlist(): BelongsTo
    {
      return $this->belongsTo(Watchlist::class);
    }

    public function media(): HasOne
    {
      return $this->hasOne(Media::class);
    }
}
