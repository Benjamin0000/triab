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

    protected $hidden = [
       
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

    public function setPosSalesHistory($amt)
    {
        // Ensure the amount is valid and sufficient stock is available
        if ($amt > $this->total) {
            throw new \Exception('Insufficient stock available to complete the sale.');
        }
    
        // Deduct the sold amount from the total stock
        $this->total -= $amt;
    
        // Create a new stock history record
        StockHistory::create([
            'product_id'    => $this->id,
            'amt'           => $amt,
            'type'          => DEBIT, // Use self::DEBIT if DEBIT is a class constant
            'desc'          => 'Sold (POS)',
            'cost_price'    => $this->cost_price, // Assuming cost_price is a property of this class
            'selling_price' => $this->selling_price, // Assuming selling_price is a property of this class
        ]);
    
        // Save the updated total to the database
        $this->save();
    }
    

}
