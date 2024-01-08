<?php

namespace App\Repositories;

use App\Models\Famiglia;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

class FamiglieRepository{
    

    public function index():Collection{
        return Famiglia::all();
    }

    public function create(Famiglia $item, array $data){
        $item->id = $data["id"];
        $item->cittadino_id = $data["cittadino_id"];
        $item->ruolo = $data["ruolo"];
        $item->responsabile = $data["responsabile"];
        $item->save();
        return $item;
    }

    
    public function update(Famiglia $item, array $data){
        $item->id = $data["id"];
        $item->cittadino_id = $data["cittadino_id"];
        $item->ruolo = $data["ruolo"];
        $item->responsabile = $data["responsabile"];
        $item->save();
        return $item;
    }


    public function delete(string $famiglia_id){
        $famiglie = Famiglia::where('id',$famiglia_id)->get();
        foreach ($famiglie as $famiglia) 
            $famiglia->softdelete();
        return ;
    }

    public function show($famiglia_id):Famiglia{
        return Famiglia::findOrFail($famiglia_id); // Se non trova lancia 404
    }


    /**
     * Verifica la condizione secondo il quale il cittadino responsabile non può lasciare la famiglia.
     * @param Famiglia $cittadino Il cittadino da controllare
     * @return boolean true se soddisfa le condizioni, false viceversa
     */
    private function verificaRequisito3CittadinoNonResponsabileFamiglia(Famiglia $cittadino){
        
        if($cittadino['responsabile'] == true) return true;
        return false;
    }

    /**
     * Verifica la condizione secondo il quale la promozione puo' avvenire solo se il cittadino e' genitore o tutore.
     * @param Famiglia $cittadino Il cittadino da controllare
     * @return boolean true se soddisfa le condizioni, false viceversa
     */
    private function verificaRequisito4ResponsabileSeFiglio(Famiglia $cittadino){
        // dd($cittadino->ruolo);
        if($cittadino->ruolo == 'figlio') return true;
        return false;
    }

    /**
     * Verifica la condizione secondo il quale i cittadini figli non possono lasciare la famiglia se sono gli unici membri di quella famiglia e non appartengono già ad altre famiglie
     * @param Famiglia $responsabileFamiglia Il cittadino da controllare
     * @param string $famiglia_id_partenza Id della famiglia di partenza
     * @return boolean true se soddisfa le condizioni, false viceversa
     */
    private function verificaRequisito5Figli(Famiglia $responsabileFamiglia, string $famiglia_id){
        if($responsabileFamiglia['ruolo'] == 'figlio'){
            // Conto quanti membri della famiglia di partenza
            if(Famiglia::where('id',$famiglia_id)->count() <= 1) return true; // new JsonResponse(["success"=>"Non e' stato possibile cambiare famiglia del cittadino in quanto unico membro della famiglia."]);
            
            // Conto di quante famiglie fa parte il cittadino
            if(Famiglia::where('cittadino_id',$responsabileFamiglia['cittadino_id'])->count() > 1) return true; // new JsonResponse(["success"=>"Non e' stato possibile cambiare famiglia del cittadino in quanto membro di più famiglie."]);
        }
        return false;
    }


    /**
     * Verifica la condizione secondo il quale Il genitore può essere responsabile di famiglie con massimo 6 membri
     * @param string $famiglia_id Id della famiglia 
     * @return boolean true se soddisfa le condizioni, false viceversa
     */
    private function verificaRequisito6MembriFamigliaMinoreDiSei(string $famiglia_id){
            // Conto di quanti membri è composta la famiglia 
            $countingFamilyMemebrs = Famiglia::where('id',$famiglia_id)->count();
            if ($countingFamilyMemebrs > 6) false;
            return true;
    }

    /**
     * Verifica la condizione secondo il quale Il genitore può essere responsabile per non più di 3 famiglie.
     * @param string $cittadino_id Id del cittadino da spostare
     * @return boolean true se soddisfa le condizioni, false viceversa
     */
    private function verificaRequisito7GenitoreResponsabileMinoreDiTreFamiglie(string $cittadino_id){
            // Conto di quante famiglie il genitore e' responsabile 
            $countingResponsability = Famiglia::where([['cittadino_id','=',$cittadino_id],['responsabile','=','true']])->count();
            if ($countingResponsability > 3) return false;
            return true;
    }



    /**
     * Ritorna tutti i membri della famiglia
     * @return Famiglia
     */
    public function getFamilyComponents(string $famiglia_id):Famiglia{
        return Famiglia::where('id',$famiglia_id)->get();
    }


    /**
     * Cambio di responsabile in famiglia
     * 
     * Vincoli:
     *  1. Ogni famiglia ha un solo cittadino responsabile
     *  2. Alla promozione a responsabile, il cittadino sostituisce un eventuale altro responsabile già definito
     *  3. La promozione puo' avvenire solo se il cittadino e' genitore o tutore
     *  4. Il genitore può essere responsabile di famiglie con massimo 6 membri
     *  5. Il genitore può essere responsabile per non più di 3 famiglie
     * 
     * @param string $famiglia_id Id della famiglia destinazione in cui spostare il cittadino
     * @param string $cittadino_id Id del cittadino da spostare
     * @return 
     */
    public function cambioResponsabileFamiglia(string $famiglia_id, string $cittadino_id) {
        //\DB::enableQueryLog();
        // Controllo se il cittadino e' genitore o tutore 
        $nuovoResponsabileFamiglia = Famiglia::where([['id','=',$famiglia_id],['cittadino_id','=',$cittadino_id],['responsabile','=',false]])->first();
        if (is_null($nuovoResponsabileFamiglia)) return new JsonResponse(["success"=>"Non e' stato possibile promuovere il cittadino"]);

        //dd($nuovoResponsabileFamiglia);

        // Se il cittadino da promuovere ha come ruolo 'filgio' non puo' essere promosso a responsabile
        if (Self::verificaRequisito4ResponsabileSeFiglio($nuovoResponsabileFamiglia) ) return new JsonResponse(["success"=>"Non e' stato possibile promuovere il cittadino in quanto ha come ruolo 'figlio'."]);
        // Requisito 3. controllato

        if ($nuovoResponsabileFamiglia->ruolo == 'genitore' ){
            
            if (Self::verificaRequisito7GenitoreResponsabileMinoreDiTreFamiglie($cittadino_id)) return new JsonResponse(["success"=>"Non e' stato possibile promuovere il cittadino in quanto e' gia' responsabile di almeno 3 famiglie."]);
            if (Self::verificaRequisito6MembriFamigliaMinoreDiSei($famiglia_id)) return new JsonResponse(["success"=>"Non e' stato possibile promuovere il cittadino in quanto la famiglia ha un numero di membri maggiore di 6."]);
        }


        // Cambio responsabile se esiste
        $cittadinoDaSostituire = Famiglia::where([['id','=',$famiglia_id],['responsabile','=',true],['cittadino_id','!=',$nuovoResponsabileFamiglia->cittadino_id]]); // update(['responsabile'=>false]);
        if( !is_null($cittadinoDaSostituire))
            $cittadinoDaSostituire->update(['responsabile'=>false]);
          
        // Setto il cittadino come nuovo responsabile di famiglia
        $nuovoResponsabileFamiglia->update(['responsabile'=>true]);



         // dd(\DB::getQueryLog());
        return new JsonResponse(["success"=>"Assegnazione cittadino come nuovo responsabile avvenuta con successo!"]);
        


        

    }

    /**
     * Spostamento di un membro da una famiglia a un'altra
     * 
     * Vincoli: 
     *  1. Lo spostamento e' possibile se il cittadino non e' responsabile della famiglia
     *  2. I cittadini figli non possono lasciare la famiglia se sono gli unici membri di quella famiglia e non appartengono già ad altre famiglie
     * 
     * @param string $famiglia_id_partenza Id della famiglia di partenza da cui spostare il cittadino
     * @param string $famiglia_id_destinazione Id della famiglia destinazione in cui spostare il cittadino
     * @param string $cittadino_id Id del cittadino da spostare
     * @return 
     * 
     * TODO: spostare messaggi di successo 
     */

    public function spostamentoCittadinoDaFamiglia(string $famiglia_id_partenza, string $famiglia_id_destinazione, string $cittadino_id ){
        // Controllo se il cittadino e' genitore o tutore 
        $responsabileFamiglia = Famiglia::where([['id','=',$famiglia_id_partenza],['cittadino_id','=',$cittadino_id]])->first();

        if($responsabileFamiglia->responsabile) return new JsonResponse(["success"=>"Non e' stato possibile cambiare famiglia del cittadino in quanto e' responsabile di famiglia."]);
        if(Self::verificaRequisito4ResponsabileSeFiglio($responsabileFamiglia)) return new JsonResponse(["success"=>"Non e' stato possibile cambiare famiglia del cittadino."]);
        $responsabileFamiglia['id'] = $famiglia_id_destinazione;
        $responsabileFamiglia->save();
        return new JsonResponse(["success"=>"Cambio famiglia avvenuto con successo!"]);
    }





    /**
     * Rimozione di un membro da una famiglia a un'altra
     * 
     * Vincoli: 
     *  1. La rimozione e' possibile se il cittadino non e' responsabile della famiglia.
     *  2. I cittadini figli non possono lasciare la famiglia se sono gli unici membri di quella famiglia e non appartengono già ad altre famiglie
     * 
     * @param string $famiglia_id Id della famiglia destinazione in cui spostare il cittadino
     * @param string $cittadino_id Id del cittadino da spostare
     */

     public function rimozioneCittadinoDaFamiglia(string $famiglia_id, string $cittadino_id ){
        $responsabileFamiglia = Famiglia::where([['id','=',$famiglia_id],['cittadino_id','=',$cittadino_id]])->first();

        if (Self::verificaRequisito3CittadinoNonResponsabileFamiglia($responsabileFamiglia)) return new JsonResponse(["success"=>"Non e' stato possibile spostare il cittadino in quanto responsabile di famiglia."]);
    
        if (Self::verificaRequisito5Figli($responsabileFamiglia, $famiglia_id)) return new JsonResponse(["success"=>"Non e' stato possibile spostare il cittadino."]);

        // Cancello il record
        if($responsabileFamiglia)
            $responsabileFamiglia->softdelete();

     }



    /**
     * Associazione di un cittadino a piu' famiglie
     * 
     * Vincoli: 
     *  
     *  
     * 
     * @param string $famiglia_id Id della famiglia destinazione in cui spostare il cittadino
     * @param string $cittadino_id Id del cittadino da spostare
     */

     public function associazioneCittadinoDaFamiglia(string $famiglia_id, string $cittadino_id ) {
        // Ottengo le informazioni del cittadino
        $data['id'] = $famiglia_id;
        $data['cittadino_id'] = $cittadino_id;
        $data['ruolo'] = 'tutore'; // TODO definire il ruolo che avra' il cittadino nella famiglia associata
        $data['responsabile'] = false; // TODO definire se sara' responsabile o no nella famiglia associata

        return new JsonResponse(Self::create(new Famiglia,$data));
     }
}