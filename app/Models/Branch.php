<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Branch extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $table = "branches";
    protected $fillable = ['name', 'email', 'phone', 'latitude', 'longitude', 'city', 'state', 'zip_code', 'address', 'status'];
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'email'     => 'string',
        'phone'     => 'string',
        'latitude'  => 'string',
        'longitude' => 'string',
        'city'      => 'string',
        'state'     => 'string',
        'zip_code'  => 'string',
        'address'   => 'string',
        'status'    => 'integer',
    ];

    public function getThumbAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('item-restaurant'))) {
            $restaurant = $this->getMedia('item-restaurant')->last();
            return $restaurant->getUrl('thumb');
        }
        return asset('images/restaurant/thumb.png');
    }

    public function getCoverAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('item-restaurant'))) {
            $restaurant = $this->getMedia('item-restaurant')->last();
            return $restaurant->getUrl('cover');
        }
        return asset('images/restaurant/cover.png');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->crop('crop-center', 112, 120)->keepOriginalImageFormat()->sharpen(10);
        $this->addMediaConversion('cover')->crop('crop-center', 260, 180)->keepOriginalImageFormat()->sharpen(10);
    }
    public function items() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Item::class);
    }
}
