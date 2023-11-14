<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image'
    ];

    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:brands,name,' . $this->id
            ],
            'image' => ['required']
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'name.unique' => 'O nome da marca já existe'
        ];
    }
}
