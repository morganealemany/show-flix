<?php

namespace App\Tests;

use App\Service\QuoteDePapa;
use PHPUnit\Framework\TestCase;

class QuoteDePapaTest extends TestCase
{

    /**
     * On va vérifier que la méthode randomQuote du service QuoteDePapa fonctionne comme prévue
     *
     * @return void
     */
    public function testRandomQuote(): void
    {
        $quoteDePapa = new QuoteDePapa();
        // Cela est censé retourner une citation aléatoire parmi la liste
        $result = $quoteDePapa->randomQuote();

        $randomQuoteArray= [
            "Le gras c'est la vie",
            "OK",
            "C'est pas faux !",
            "Vouala",
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
        // Pour que le test fonctionne, le résultat de la méthode doit se trouve dans le tableau randomQuoteArray
        // https://phpunit.readthedocs.io/fr/latest/assertions.html#assertcontains
        $this->assertContains($result, $randomQuoteArray);
    }
}
