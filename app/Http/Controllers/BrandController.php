<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brandRepository = new BrandRepository($this->brand);

        if ($request->has('attributes_models')) {
            $attributes_models = 'carModels:id,' . $request->attributes_models;
            $brandRepository->selectAttributesRegistrationRelated($attributes_models);
        } else {
            $brandRepository->selectAttributesRegistrationRelated('carModels');
        }

        if ($request->has('filter')) {
            $brandRepository->filter($request->filter);
        }

        if ($request->has('attributes_brand')) {
            $brandRepository->selectAttributes($request->attributes_brand);
        }

        return response()->json(
            $brandRepository->getResultPaginate(5),
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->brand->rules(), $this->brand->feedback());
        $image = $request->file('image');
        $image_urn = $image->store('images', 'public');

        $brand = $this->brand->create([
            'name' => $request->name,
            'image' => $image_urn
        ]);

        return response()->json($brand, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $brand = $this->brand->with('carModels')->find($id);
        if ($brand === null) {
            return response()->json(
                [
                    'error' => 'Marca não encontrada'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json($brand, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = $this->brand->find($id);
        if ($brand === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar atualização. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = array();

            foreach ($brand->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules, $brand->feedback());
        } else {
            $request->validate($brand->rules(), $brand->feedback());
        }

        if ($request->file('image')) {
            Storage::disk('public')->delete($brand->image);
        }

        $image = $request->file('image');
        $image_urn = $image->store('images', 'public');

        $brand->fill($request->all());
        $brand->image = $image_urn;
        $brand->save();
        /*
        $brand->update([
            'name' => $request->name,
            'image' => $image_urn
        ]);
        */
        return response()->json($brand, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = $this->brand->find($id);
        if ($brand === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar a exclusão. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        Storage::disk('public')->delete($brand->image);

        $this->brand->delete();
        return response()->json(
            [
                'mensagem' => 'Marca removida com sucesso!'
            ],
            Response::HTTP_OK
        );
    }
}
