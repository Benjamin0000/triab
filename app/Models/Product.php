<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Product extends Model
{
    use Uuids;

    protected $casts = [
        'images' => 'string', // Store images as a comma-separated string
    ];

    protected $fillable = [
        'shop_id',
        'productID',
        'name',
        'images',
        'parent_id',
        'cost_price',
        'selling_price',
        'type',
        'description'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function total_childeren()
    {
        return self::where('parent_id', $this->id)->count();
    }

    public function has_children()
    {
        return self::where('parent_id', $this->id)->exists();
    }
    /**
     * Accessor to return the images as an array.
     */
    public function getImagesAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }
    /**
     * Mutator to store images as a comma-separated string.
     */
    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = is_array($value) ? implode(',', $value) : $value;
    }
    /**
     * Add a new image path to the images list.
     */
    public function addImage(string $path)
    {
        $images = $this->images;
        if (!in_array($path, $images)) {
            $images[] = $path;
            $this->images = $images;
            $this->save();
        }
    }
    /**
     * Remove an image path from the images list.
     */
    public function removeImage(string $path)
    {
        $images = $this->images;
        $filtered = array_filter($images, fn($image) => $image !== $path);
        $this->images = $filtered;
        $this->save();
    }

    /**
     * Get the first image to serve as the poster.
     */
    public function getPosterImage(): ?string
    {
        return $this->images[0] ?? null;
    }

}
