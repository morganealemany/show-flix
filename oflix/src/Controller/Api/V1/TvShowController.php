<?php

namespace App\Controller\Api\V1;

use App\Entity\TvShow;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/tvshows", name="api_v1_tvshow_")
 */
class TvShowController extends AbstractController
{
    /**
     * URL : /api/v1/tvshows/
     * Route : api_v1_tvshow_index
     * 
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(TvShowRepository $tvShowRepository): Response
    {
        // On récupère les séries stockées en BDD
        $tvShows = $tvShowRepository->findAll();

        // dd($tvShows);

        // On retourne la liste au format JSON
        return $this->json($tvShows, 200, [], [
            // Cette entrée va permettre au serializer de transformer les objets en JSON, en allant chercher uniquement les propriétés taggées avec les nom 'tvshows'
            'groups' => 'tvshows'
        ]);
    }

    /**
     * Retourne les informations d'une série en fonction de son ID
     * 
     * @Route("/{id}", name="show", methods={"GET"})
     * 
     * @return JsonResponse
     */
    public function show(int $id, TvShowRepository $tvShowRepository)
    {
        // On récupère une série en fonction de son id
        $tvShow = $tvShowRepository->find($id);

        // Si la série n'existe pas, on retourne une erreur 404 mais en JSON, pas en HTML
        if (!$tvShow) {
            return $this->json([
                'error' => 'La série ' . $id . ' n\'existe pas'
            ], 404);
        }
        return $this->json($tvShow, 200, [], [
            'groups' => 'tvshow_detail'
        ]);
    }

    /**
     * Permet la création d'une nouvelle série
     * 
     * @Route("/", name="add", methods={"POST"})
     *
     * @return void
     */
    public function add(Request $request, SerializerInterface $serialiser)
    {
        // Etape 1 : On récupère le JSON
        $jsonData = $request->getContent();

        // Etape 2 : On transforme le json en objet : desérialisation
        // On indique les données à desérialiser (transformer), puis le format d'arrivée après transformation (objet de type TvShow), enfin on indique le format de départ (avant transformation): on veut passer du format json vers un objet
        $newTvShow = $serialiser->deserialize($jsonData, TvShow::class, 'json');

        // Pour sauvergarder, on appelle le manager
        $em = $this->getDoctrine()->getManager();
        $em->persist($newTvShow);
        $em->flush();

        // On retourne une réponse en indiquant que la ressource
        // a bien été créée (code http 201)
        return $this->json($newTvShow, 201);

    }
}
