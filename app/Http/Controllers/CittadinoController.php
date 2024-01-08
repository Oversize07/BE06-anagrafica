<?php

namespace App\Http\Controllers;

use App\Http\Requests\CittadinoInsertRequest;
use Illuminate\Http\Request;
use App\Models\Cittadino;
use App\Repositories\CittadiniRepository;
use Illuminate\Http\JsonResponse;



class CittadinoController extends Controller
{

    public function __construct(private CittadiniRepository $cittadiniRepository,
                                ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new JsonResponse($this->cittadiniRepository->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CittadinoInsertRequest $request)
    {
        $data = $request->all();
        $item = $this->cittadiniRepository->create(new Cittadino,$data);
        return new JsonResponse($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new JsonResponse($this->cittadiniRepository->show($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $item = $this->cittadiniRepository->show($data['id']);
        $item = $this->cittadiniRepository->update($item,$data);
        return new JsonResponse($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->cittadiniRepository->delete($id);
        return new JsonResponse(["success"=>true]);
    }


}
