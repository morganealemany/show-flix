<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Service permettant de récupérer des informations depuis le site https://omdbapi.com/
 */
class OmdbApi 
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
    public function fetch(string $title)
    {
        // On va passer en mode "client" pour interroger l'API
        // grâce à la classe HTTP Client
        
        $response = $this->client->request(
            'GET',
            'https://omdbapi.com/?apiKey=bc69b144&t=' . $title,
        );
        // dd($response);

        // On retourne les informations de la série sous la forme
        // d'un tableau associatif

        return $response->toArray();
    }
}