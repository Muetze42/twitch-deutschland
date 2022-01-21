<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Video
 *
 * @property int $id
 * @property int $channel_id
 * @property string $youtube_id
 * @property string $description
 * @property int $position
 * @property int $batch
 * @property int $new_video_notified
 * @property Carbon $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Channel $channel
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Video newModelQuery()
 * @method static Builder|Video newQuery()
 * @method static Builder|Video query()
 * @method static Builder|Video whereBatch($value)
 * @method static Builder|Video whereChannelId($value)
 * @method static Builder|Video whereCreatedAt($value)
 * @method static Builder|Video whereDeletedAt($value)
 * @method static Builder|Video whereDescription($value)
 * @method static Builder|Video whereId($value)
 * @method static Builder|Video whereNewVideoNotified($value)
 * @method static Builder|Video wherePosition($value)
 * @method static Builder|Video wherePublishedAt($value)
 * @method static Builder|Video whereUpdatedAt($value)
 * @method static Builder|Video whereYoutubeId($value)
 * @mixin Eloquent
 * @property string $title
 * @method static Builder|Video whereTitle($value)
 * @property-read Collection|Broadcaster[] $broadcasters
 * @property-read int|null $broadcasters_count
 * @method static Builder|Video ordered()
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 */
class Video extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'youtube_id',
        'title',
        'description',
        'batch',
        'new_video_notified',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumb')
            ->singleFile();
    }

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('public')
            ->width(300)
            ->crop(Manipulations::CROP_CENTER, 300, 169)
            ->nonQueued();
    }

    /**
     * Get the channel that owns the video.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * The users that belong to the video.
     */
    public function broadcasters(): BelongsToMany
    {
        return $this->belongsToMany(Broadcaster::class)->orderBy('display_name');
    }

    /**
     * Scope a query to get videos ordered
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }
}
