<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_model_id',
        'plate',
        'available',
        'km'
    ];

    public function rules()
    {
        return [
            'car_model_id' => 'exists:brands,id',
            'plate' => [
                'required',
            ],
            'available' => [
                'required',
                'boolean'
            ],
            'km' => [
                'required'
            ]
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'boolean' => 'O campo :attribute precisa ser verdadeiro ou falso'
        ];
    }

    public function carModel()
    {
        return $this->belongsTo('App\Models\carModel', 'car_model_id', 'id');
    }
}
