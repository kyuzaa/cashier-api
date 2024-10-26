<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $fillable = ['name', 'price', 'stock', 'category_id', 'image'];

    // Hidden attributes
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Appended attributes
    protected $appends = ['image_url'];

    // Cast attributes
    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'category_id' => 'integer'
    ];

    // Relasi dengan category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Get image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return url(Storage::url($this->image));
        }
        return null;
    }

    // Load default relations
    protected $with = ['category'];
}
