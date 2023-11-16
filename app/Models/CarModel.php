<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'name',
        'image',
        'number_doors',
        'places',
        'air_bag',
        'abs'
    ];

    public function rules()
    {
        return [
            'brand_id' => 'exists:brands,id',
            'name' => [
                'required',
                'unique:car_models,name,' . $this->id
            ],
            'image' => [
                'required',
                'file',
                'mimes:png,jpeg,jpg'
            ],
            'number_doors' => [
                'required',
                'integer',
                'digits_between:1,5'
            ],
            'places' => [
                'required',
                'integer',
                'digits_between:1,4'
            ],
            'air_bag' => [
                'required',
                'boolean'
            ],
            'abs' => [
                'required',
                'boolean'
            ],
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'name.unique' => 'O nome da marca já existe',
            'image.mimes' => 'A imagem dever ser do tipo PNG, JPEG ou JPG',
            'integer' => 'O campo :attribute precisa ser um número inteiro',
            'boolean' => 'O campo :attribute precisa ser verdadeiro ou falso'
        ];
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }
}
