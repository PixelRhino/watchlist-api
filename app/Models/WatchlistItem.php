<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $watchlist_id
 * @property int $media_id
 * @property int $name
 * @property int $season
 * @property int $episode
 * @property int $created_at
 * @property int $updated_at
 * @property Watchlist $watchlist
 */

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

    protected $casts = [
        "season" => 'integer',
        "episode" => 'integer',
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
