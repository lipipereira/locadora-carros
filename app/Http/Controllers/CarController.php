<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Repositories\CarRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CarController extends Controller
{
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carRepository = new CarRepository($this->car);

        if ($request->has('attributes_model')) {
            $attributes_model = 'carModel:id,' . $request->attributes_model;
            $carRepository->selectAttributesRegistrationRelated($attributes_model);
        } else {
            $carRepository->selectAttributesRegistrationRelated('carModel');
        }

        if ($request->has('filter')) {
            $carRepository->filter($request->filter);
        }

        if ($request->has('attributes_brand')) {
            $carRepository->selectAttributes($request->attributes_brand);
        }

        return response()->json(
            $carRepository->getResult(),
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->car->rules(), $this->car->feedback());
        $car = $this->car->create([$request->all()]);
        return response()->json($car, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $car = $this->car->with('carModel')->find($id);
        if ($car === null) {
            return response()->json(
                [
                    'error' => 'Marca não encontrada'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json($car, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $car = $this->car->find($id);
        if ($car === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar atualização. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = array();

            foreach ($car->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules, $car->feedback());
        } else {
            $request->validate($car->rules(), $car->feedback());
        }

        $car->fill($request->all());
        $car->save();
        return response()->json($car, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);
        if ($car === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar a exclusão. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->car->delete();
        return response()->json(
            [
                'mensagem' => 'O carro removida com sucesso!'
            ],
            Response::HTTP_OK
        );
    }
}
