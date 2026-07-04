<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $thumb_images
 * @property string|null $note
 * @property string|null $gallery
 * @property string $descriptions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ActivitiesFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereDescriptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereGallery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereThumbImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activities whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Activities extends Model {
        use HasFactory;

        protected $guarded = [];
    }
