<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Channel
 *
 * @property int $id
 * @property string $youtube_id
 * @property string|null $name
 * @property Carbon|null $subscribed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|\App\Models\Video[] $videos
 * @property-read int|null $videos_count
 * @method static Builder|Channel newModelQuery()
 * @method static Builder|Channel newQuery()
 * @method static Builder|Channel obsoleteData()
 * @method static Builder|Channel obsoleteSubscription()
 * @method static \Illuminate\Database\Query\Builder|Channel onlyTrashed()
 * @method static Builder|Channel query()
 * @method static Builder|Channel whereCreatedAt($value)
 * @method static Builder|Channel whereDeletedAt($value)
 * @method static Builder|Channel whereId($value)
 * @method static Builder|Channel whereName($value)
 * @method static Builder|Channel whereSubscribedAt($value)
 * @method static Builder|Channel whereUpdatedAt($value)
 * @method static Builder|Channel whereYoutubeId($value)
 * @method static \Illuminate\Database\Query\Builder|Channel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Channel withoutTrashed()
 * @mixin Eloquent
 * @property bool $idle
 * @method static Builder|Channel whereIdle($value)
 * @property-read \App\Models\Video|null $latestVideo
 */
class Channel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'youtube_id',
        'name',
        'idle',
        'subscribed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'idle'          => 'boolean',
        'subscribed_at' => 'datetime',
    ];

    /**
     * Get the videos for the channel.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function latestVideo(): HasOne
    {
        return $this->hasOne(Video::class)->orderByDesc('published_at');
    }

    /**
     * Scope a query to get channels without or obsolete YouTube Webhook subscriptions
     *
     * @param Builder $query
     * @return void
     */
    public function scopeObsoleteSubscription(Builder $query)
    {
        $query->whereNull('subscribed_at')->orWhere('subscribed_at', '<=', now()->subWeek());
    }

    /**
     * Scope a query to get channels which should updated (search for new or deleted YouTube videos)
     *
     * @param Builder $query
     * @return void
     */
    public function scopeObsoleteData(Builder $query)
    {
        $query->whereNull('subscribed_at')->orWhere('updated_at', '<=', now()->subHour());
    }
}
