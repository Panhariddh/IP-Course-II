<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    protected $table = 'orders';

    protected $fillable = ['customer_id', 'total_price', 'order_date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }


    protected function orderDate(): Attribute
    {
    return Attribute::make(
        // Mutator: Convert input format to MySQL format before saving
        set: fn($value) => 
            // Check if value is a Carbon instance, if not, format it
            $value instanceof Carbon ? $value->format('Y-m-d H:i:s') : Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s'),

        // Accessor: Convert database format to user format when retrieving
        get: fn($value) => Carbon::parse($value)->format('d/m/Y H:i:s')
        );
    }
}
