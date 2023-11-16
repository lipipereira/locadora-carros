<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CarModelController extends Controller
{
    public function __construct(CarModel $carModel)
    {
        $this->carModel = $carModel;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carModels = array();
        if ($request->has('attributes_brand')) {
            $attributes_brand = $request->attributes_brand;
            $carModels = $this->carModel->with('brand:id,' . $attributes_brand);
        } else {
            $carModels = $this->carModel->with('brand');
        }

        if ($request->has('filter')) {
            $filters = explode(';', $request->filter);
            foreach ($filters as $key => $filter) {
                $where = explode(':', $filter);
                $carModels = $carModels->where($where[0], $where[1], $where[2]);
            }
        } else {
            $carModels = $this->carModel->get();
        }

        if ($request->has('attributes_model')) {
            $attributes_model = $request->attributes_model;
            $carModels = $carModels
                ->selectRaw($attributes_model)
                ->get();
        } else {
            $carModels = $carModels->get();
        }

        return response()->json(
            $carModels,
            //$this->carModel->with('brand')->get(),
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->carModel->rules(), $this->carModel->feedback());
        $image = $request->file('image');
        $image_urn = $image->store('images/carModels', 'public');

        $carModel = $this->carModel->create([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'image' => $image_urn,
            'number_doors' => $request->number_doors,
            'places' => $request->places,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($carModel, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carModel = $this->carModel->with('brand')->find($id);
        if ($carModel === null) {
            return response()->json(
                [
                    'error' => 'Marca não encontrada'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json($carModel, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $carModel = $this->carModel->find($id);
        if ($carModel === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar atualização. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = array();

            foreach ($carModel->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules, $carModel->feedback());
        } else {
            $request->validate($carModel->rules(), $carModel->feedback());
        }

        if ($request->file('image')) {
            Storage::disk('public')->delete($carModel->image);
        }

        $image = $request->file('image');
        $image_urn = $image->store('images/carModels', 'public');

        $carModel->fill($request->all());
        $carModel->image = $image_urn;
        $carModel->save();

        /*
        $carModel->update([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'image' => $image_urn,
            'number_doors' => $request->number_doors,
            'places' => $request->places,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        */
        return response()->json($carModel, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $carModel = $this->carModel->find($id);
        if ($carModel === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar a exclusão. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        Storage::disk('public')->delete($carModel->image);

        $this->carModel->delete();
        return response()->json(
            [
                'mensagem' => 'Marca removida com sucesso!'
            ],
            Response::HTTP_OK
        );
    }
}
