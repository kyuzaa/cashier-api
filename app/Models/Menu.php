<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $table = "products";
<<<<<<< HEAD
    protected $fillable = ['name', 'price', 'stock', 'category_id', 'image', 'slug'];
=======
    protected $fillable = ['name', 'price', 'stock', 'category_id', 'image'];
>>>>>>> 6decea440c456a0f635695f0c2f9c5e9d191b254

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
