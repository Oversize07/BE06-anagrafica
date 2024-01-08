<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cittadino;
use App\Models\Famiglia;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FamigliaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ruolo = ['genitore','tutore','figlio'];
        $famigliaId = rand();
        // // Controlla se esiste un responsabile per quella famiglia
        // if (Famiglia::find($famigliaId)->where('responsabile','true')) $puoEssereResponsabile = false; 
        // else $puoEssereResponsabile = true; // Non c'e' ancora un responsabile per la famiglia
        $cittadinoRandom = Cittadino::inRandomOrder()->first(); // Prende un cittadino a caso nel db
        $role = $ruolo[rand(0,count($ruolo)-1)];
        $responsable =  fake()->boolean();
        if ($role == 'figlio') $responsable = false;
        return [
            'id'=>$famigliaId,
            'cittadino_ID'=> $cittadinoRandom->id,
            'ruolo'=> $role,
            'responsabile'=> $responsable
        ];
    }
}
