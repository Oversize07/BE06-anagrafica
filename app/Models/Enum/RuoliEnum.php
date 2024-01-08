<?php

namespace App\Models\Enum;

class RuoliEnum {

    const GENITORE = "genitore";
    const TUTORE = "tutore";
    const FIGLIO = "figlio";

    public static function all() {
        return [
            static::GENITORE,
            static::TUTORE,
            static::FIGLIO,
        ];
    }
}

