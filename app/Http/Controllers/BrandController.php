<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandController extends Controller
{

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            $this->brand->all(),
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->brand->rules(), $this->brand->feedback());

        return response()->json(
            $this->brand->create($request->all()),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $brand = $this->brand->find($id);
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

        $brand->update($request->all());
        return response()->json(
            $brand,
            Response::HTTP_OK
        );
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

        $this->brand->delete();
        return response()->json(
            ['mensagem' => 'Marca removida com sucesso!'],
            Response::HTTP_OK
        );
    }
}
