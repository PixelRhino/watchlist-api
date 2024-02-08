<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $name
 * @property int $public
 * @property int $created_at
 * @property int $updated_at
 * @property User $user
 */

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    protected $hidden = ["user_id", "user"];

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
