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
            "Je vais me suicider avec des yaourts périmés.
            Chandler",
            "Imagine que Martin Luther King ait dit : « J’ai fait un rêve... mais j’ai pas envie d’en parler ».
            Chandler",
            "Ils ne savent pas qu’on sait qu’ils savent qu’on sait !
            Rachel",
            "J'ai mal à la pomme de Joey !
            Joey"

        ];

        // Retourne un index aléatoire
        $randomIndex = array_rand($randomQuoteArray);

        // On retourne une citation parmi la liste $randomQuoteArray
        return $randomQuoteArray[$randomIndex];
    }
}