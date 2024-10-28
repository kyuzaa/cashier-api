<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $fillable = ['name', 'price', 'stock', 'category_id', 'image', 'slug'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = ['image_url'];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'category_id' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return url(Storage::url($this->image));
        }
        return null;
    }

    protected $with = ['category'];
}
