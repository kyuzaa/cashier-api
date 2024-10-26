<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['total_amount', 'status', 'items', 'nomor_meja', 'tanggal'];

    // Cast attributes
    protected $casts = [
        'items' => 'array',
        'tanggal' => 'datetime:Y-m-d H:i:s',
        'total_amount' => 'float',
        'status' => 'string'
    ];

    // Hidden attributes
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;

    // Accessor untuk status dalam bentuk text
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            '0' => 'Pending',
            '1' => 'Processing',
            '2' => 'Completed',
            default => 'Unknown'
        };
    }

    // Append status text ke JSON
    protected $appends = ['status_text'];
}
