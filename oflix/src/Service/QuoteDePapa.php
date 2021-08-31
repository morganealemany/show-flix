<?php

namespace App\Service;

class QuoteDePapa 
{

    /**
     * Retourne des citations de dev
     *
     * @return string
     */
    public function randomQuote(): string 
    {
        $randomQuoteArray= [
            "Le gras c'est la vie",
            "OK",
            "C'est pas faux !",
            "Des customs curry",
            "RTFM",
            "auto waouwaouwaing",
            "search('homme poilu')",
            "Toutou beignet",
            "cateogry",
            "Quand tu ajoute tu attribut à la manau, c'est l'attribut de Dana",
            "j'oublie vite les choses",
            "un clavier AZERTY en vaux deux",
            "Flash sur Firefox, c’est de l’Adobe !",
            "la technique du saumon hydrater un objet",
            "peut-être= sûr",
            "on est les ylusses!"
        ];

        // Retourne un index aléatoire
        $randomIndex = array_rand($randomQuoteArray);

        // On retourne une citation parmi la liste $randomQuoteArray
        return $randomQuoteArray[$randomIndex];
    }
}