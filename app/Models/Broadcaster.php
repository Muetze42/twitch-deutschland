<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Broadcaster
 *
 * @property int $id
 * @property int $twitch_id
 * @property string $name
 * @property string $display_name
 * @property string|null $logo
 * @property int $video_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Video[] $videos
 * @property-read int|null $videos_count
 * @method static Builder|Broadcaster newModelQuery()
 * @method static Builder|Broadcaster newQuery()
 * @method static Builder|Broadcaster ordered()
 * @method static Builder|Broadcaster query()
 * @method static Builder|Broadcaster whereCreatedAt($value)
 * @method static Builder|Broadcaster whereDisplayName($value)
 * @method static Builder|Broadcaster whereId($value)
 * @method static Builder|Broadcaster whereLogo($value)
 * @method static Builder|Broadcaster whereName($value)
 * @method static Builder|Broadcaster whereTwitchId($value)
 * @method static Builder|Broadcaster whereUpdatedAt($value)
 * @method static Builder|Broadcaster whereVideoCount($value)
 * @mixin \Eloquent
 * @property array|null $old_names
 * @method static Builder|Broadcaster whereOldNames($value)
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 */
class Broadcaster extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'twitch_id',
        'name',
        'display_name',
        'logo',
        'old_names',
        'video_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'video_count' => 'int',
        'old_names'   => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }

    /**
     * The videos that belong to the user.
     */
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class)->orderByDesc('published_at');
    }

    /**
     * Scope a query to get broadcasters ordered by video count
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('video_count')->orderByDesc('updated_at');
    }
}
