<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "category";
    protected $fillable = ['name', 'slug'];

    public $timestamps = false;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function products()
    {
        return $this->hasMany(Menu::class);
    }

    public function toArray()
    {
        $array = parent::toArray();
        if ($this->relationLoaded('products')) {
            $array['products'] = $this->products;
        }
        return $array;
    }
}
