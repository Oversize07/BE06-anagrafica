<?php

namespace App\Http\Controllers;

use App\Http\Requests\FamigliaInsertRequest;
use App\Models\Famiglia;
use Illuminate\Http\Request;
use App\Repositories\FamiglieRepository;
use Illuminate\Http\JsonResponse;

class FamigliaController extends Controller
{

    public function __construct(private FamiglieRepository $famiglieRepository) {}



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new JsonResponse($this->famiglieRepository->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FamigliaInsertRequest $request)
    {
        $data = $request->all();
        $item = $this->famiglieRepository->create(new Famiglia,$data);
        return new JsonResponse($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new JsonResponse($this->famiglieRepository->show($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $item = $this->famiglieRepository->show($data['id']);
        $item = $this->famiglieRepository->update($item,$data);
        return new JsonResponse($item);
    }

    /**
     * Remove the specified resource from storage.
     * TODO rimozione di tutte le famiglie con quell'id
     */
    public function destroy(string $id)
    {
        $this->famiglieRepository->delete($id);
        return new JsonResponse(["success"=>true]);
    }


    /**
     * Promozione a responsabile di un membro di una famiglia
     */
    public function promozioneResponsabileFamiglia(string $famiglia_id,string $cittadino_id ){
        return $this->famiglieRepository->cambioResponsabileFamiglia($famiglia_id,$cittadino_id);

    }

    /**
     *Spostamento di un cittadino da una famiglia a un'altra
     */
    public function spostamentoCittadinoDaFamiglia(string $famiglia_id_partenza, string $famiglia_id_destinazione, string $cittadino_id){
        return $this->famiglieRepository->spostamentoCittadinoDaFamiglia($famiglia_id_partenza,$famiglia_id_destinazione,$cittadino_id);

    }

    /**
     * Rimozione di un cittadino da una famiglia
     */
    public function rimozioneCittadinoDaFamiglia(string $famiglia_id,string $cittadino_id ){
        return $this->famiglieRepository->rimozioneCittadinoDaFamiglia($famiglia_id,$cittadino_id);

    }

    /**
     * Associazione di un cittadino ad un'altra famiglia senza lasciare la corrente
     */
    public function associazioneCittadinoAPiuFamiglie(string $famiglia_id,string $cittadino_id ){
        return $this->famiglieRepository->associazioneCittadinoDaFamiglia($famiglia_id,$cittadino_id);

    }
}
