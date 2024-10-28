<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    protected $appends = ['subtotal'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Menu::class, 'product_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * ($this->product ? $this->product->price : 0);
    }

    // Load default relations
    protected $with = ['product'];
}
