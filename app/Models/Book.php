<?php

namespace App\Models;

use App\Models\Writer;
use App\Models\Product;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $subject
 * @property int|null $publisher_id
 * @property string $isbn
 * @property string $edition
 * @property int|null $pages
 * @property string|null $cover
 * @property string|null $country
 * @property string|null $language
 * @property string|null $sample_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product $product
 * @property-read Publisher|null $publisher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Writer> $writers
 * @property-read int|null $writers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereEdition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePublisherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereSamplePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Book extends Model
{
    protected $guarded = [];

    public function writers()
    {
        return $this->belongsToMany(Writer::class, 'book_writer', 'book_id', 'writer_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
