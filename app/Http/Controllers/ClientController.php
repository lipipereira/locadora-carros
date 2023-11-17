<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clientRepository = new ClientRepository($this->client);

        if ($request->has('filter')) {
            $clientRepository->filter($request->filter);
        }

        if ($request->has('attributes_client')) {
            $clientRepository->selectAttributes($request->attributes_client);
        }

        return response()->json(
            $clientRepository->getResult(),
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->client->rules(), $this->client->feedback());
        $client = $this->client->create(['name' => $request->name]);
        return response()->json($client, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = $this->client->find($id);
        if ($client === null) {
            return response()->json(
                [
                    'error' => 'Cliente não encontrada'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json($client, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $client = $this->client->find($id);
        if ($client === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar atualização. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = array();

            foreach ($client->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules, $client->feedback());
        } else {
            $request->validate($client->rules(), $client->feedback());
        }

        $client->fill($request->all());
        $client->save();
        return response()->json($client, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = $this->client->find($id);
        if ($client === null) {
            return response()->json(
                [
                    'error' => 'Impossivel realizar a exclusão. O recurso solicitado não existe'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->client->delete();
        return response()->json(
            [
                'mensagem' => 'O Cliente removida com sucesso!'
            ],
            Response::HTTP_OK
        );
    }
}
