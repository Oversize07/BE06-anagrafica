<?php

namespace App\Repositories;

use App\Models\Cittadino;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

class CittadiniRepository{
    

    public function index():Collection{
        return Cittadino::all();
    }

    public function create(Cittadino $item, array $data){
        $item->nome = $data["nome"];
        $item->cognome = $data["cognome"];
        $item->codiceFiscale = $data["codiceFiscale"];
        $item->save();
        return $item;
    }

    
    public function update(Cittadino $item, array $data){
        $item->nome = $data["nome"];
        $item->cognome = $data["cognome"];
        $item->codiceFiscale = $data["codiceFiscale"];
        $item->save();
        return $item;
    }


    public function delete(string $id){
        $item = Cittadino::find($id);
        if($item)
            $item->delete();
    }

    public function show($id):Cittadino{
        return Cittadino::findOrFail($id); // Se non trova lancia 404
    }



    /**
     * Ritorna tutti i membri della Cittadino
     * @return Cittadino
     */
    public function getFamilyComponents(string $Cittadino_id):Cittadino{
        return Cittadino::where('id',$Cittadino_id)->get();
    }



}