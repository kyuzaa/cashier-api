<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "category";
<<<<<<< HEAD
    protected $fillable = ['name', 'slug'];
=======
    protected $fillable = ['name'];
>>>>>>> 6decea440c456a0f635695f0c2f9c5e9d191b254

    public $timestamps = false;

    // Hidden attributes
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relasi dengan products
    public function products()
    {
        return $this->hasMany(Menu::class);
    }

    // Load products when requested
    public function toArray()
    {
        $array = parent::toArray();
        if ($this->relationLoaded('products')) {
            $array['products'] = $this->products;
        }
        return $array;
    }
}
