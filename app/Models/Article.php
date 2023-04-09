<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the article.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['author_id', 'id'];
}
