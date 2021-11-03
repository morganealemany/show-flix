<?php

namespace App\Controller;

use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/search", name="search_")
 */
class SearchController extends AbstractController
{
    /**
     * Affiche les résultats d'une recherche
     * 
     * @Route("/", name="index")
     */
    public function index(Request $request, TvShowRepository $repositoryTvShow): Response
    {
        // 1) On récupére le mot clé saisi par l'utilisateur dans le formulaire
        $query = $request->query->get('search');
        // dd($query);
        // 2) On récupére toutes les séries qui contiennent ce mot clé
        $results = $repositoryTvShow->searchTvShowByTitle($query);

        // dd($results);
        // 3) On affiche ensuite le résultat depuis la page /search
        return $this->render('search/index.html.twig', [
            'results' => $results,
        ]);
    }
}
