<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Service permettant de récupérer des informations depuis le site https://omdbapi.com/
 */
class BetaserieApi 
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Récupére les informations d'une série via l'api omdbapi en fonction du titre
     *
     * @param string $title
     * @return Array
     */
    public function fetchTvShowId(string $title)
    {
        // On va passer en mode "client" pour interroger l'API
        // grâce à la classe HTTP Client
        
        $response = $this->client->request(
            'GET',
            'http://api.betaseries.com/search/shows?v=3.0&key=ade7d1355ff8&text=' . $title,
        );
        // dd($response);

        // On retourne les informations de la série sous la forme
        // d'un tableau associatif

        return $response->toArray();
    }

    public function fetchTvShowData(int $id)
    {
        $response = $this->client->request(
            'GET',
            'http://api.betaseries.com/shows/display?v=3.0&key=ade7d1355ff8&id=' . $id,
        );
        return $response->toArray();
    }
}