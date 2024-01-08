<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CittadinoFactory extends Factory
{
    /**
     * Genera una stringa
     *
     * @return string
     */
    function generateRandomString($length) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * Genera un codice fiscale fake
     *
     * @return string
     */
    public function CFGenerator(): string{
        $nomeCognome = Self::generateRandomString(6); // Generaaione caratteri per nome e cognome
        $annoNascita = rand(0, 99); // Generazione anno nascita
        $meseNascita = Self::generateRandomString(1); // TODO filtrare lettere non indicanti effettivamente mesi
        
        if ($meseNascita == 'B') $giornoNascita = rand(1, 28); // TODO: controllare se anno bisestile
        else $giornoNascita = rand(1, 31);

        $comuneCarattere = Self::generateRandomString(1);
        $comuneNumerico = rand(100,999);

        $controllo = Self::generateRandomString(1); // TODO implementare controllo per ottenere carattere
    
        return $nomeCognome . $annoNascita . $meseNascita. $giornoNascita . $comuneCarattere . $comuneNumerico .$controllo;

    }

    /**
     * Define the model's default state.
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome'=>$this->faker->name(),
            'cognome'=>$this->faker->name(),
            "codiceFiscale"=> self::CFgenerator() // TODO combinare nome e cognome generati con codice fiscale
        ];
    }
}
