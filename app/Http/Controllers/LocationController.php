<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    public function __construct(Location $location)
    {
        $this->location = $location;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locationRepository = new LocationRepository($this->location);

        if ($request->has('filter')) {
            $locationRepository->filter($request->filter);
        }

        if ($request->has('attributes_location')) {
            $locationRepository->selectAttributes($request->attributes_location);
        }

        return response()->json(
            $locationRepository->getResult(),
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->location->rules(), $this->location->feedback());
        $location = $this->location->create([$request->all()]);
        return response()->json($location, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $location = $this->location->find($id);
        if ($location === null) {
            return response()->json(
                [
                    'error' => 'Marca não encontrada'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json($location, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $location = $this->location->find($id);
        if ($location === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar atualização. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = array();

            foreach ($location->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules, $location->feedback());
        } else {
            $request->validate($location->rules(), $location->feedback());
        }

        $location->fill($request->all());
        $location->save();
        return response()->json($location, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $location = $this->location->find($id);
        if ($location === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar a exclusão. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->location->delete();
        return response()->json(
            [
                'mensagem' => 'Locação removida com sucesso!'
            ],
            Response::HTTP_OK
        );
    }
}
