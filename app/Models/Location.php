<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'car_id',
        'start_date_period',
        'final_date_expected_period',
        'end_date_realized_period',
        'daily_value',
        'initial_km',
        'km_final'
    ];

    public function rules()
    {
        return [
            'client_id' => [
                'required',
                'exists:clients,id'
            ],
            'car_id' => [
                'required',
                'exists:cars:id'
            ],
            'start_date_period' => [
                'required',
                'date'
            ],
            'final_date_expected_period' => [
                'required',
                'date'
            ],
            'end_date_realized_period' => [
                'required',
                'date'
            ],
            'daily_value' => [
                'required',
                'integer'
            ],
            'initial_km' => [
                'required',
                'integer'
            ],
            'km_final' => [
                'required',
                'integer'
            ]
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'integer' => 'O campo :attribute precisa número inteiro',
            'date' => 'O campo :attribute precisa ser uma data'
        ];
    }
}
