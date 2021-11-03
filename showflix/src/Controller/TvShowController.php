<?php

namespace App\Controller;

use App\Entity\TvShow;
use App\Repository\SeasonRepository;
use App\Repository\TvShowRepository;
use App\Service\BetaserieApi;
use App\Service\OmdbApi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/tvshow", name="tvshow_")
 * 
 * @IsGranted("ROLE_USER")
 */
class TvShowController extends AbstractController
{
    /**
     * Page affichant la liste des séries
     * 
     * @Route("/", name="index")
     */
    public function index (TvShowRepository $repositoryTvShow): Response 
    {
        // Pour afficher la liste des séries, il faut être connecté
        // $this->denyAccessUnlessGranted('ROLE_USER');

        $list = $repositoryTvShow->findAll();

        return $this->render('tv_show/index.html.twig', [
            'list' => $list,
        ]);
    }

    /**
     * Méthode affichant les détails d'une série en fonction de son id
     * 
     * @Route("/{id}", name="show", requirements={"id": "\d+"})
     * @Route("/{slug}", name="slug")
     *
     * @return Response
     */
    public function show(TvShow $tvshow,BetaserieApi $betaserieApi, OmdbApi $omdbApi): Response
    {
        // On autorise l'accès aux détails d'une série uniquement
        // aux personnes connectées
        // $this->denyAccessUnlessGranted('ROLE_USER');

        // $this->denyAccessUnlessGranted('ROLE_USER');

        // Récupération des infos de la série dont l'id est passée en argument
        // $tvshow = $repositoryTvShow->find($id);


        // Test du service BetaserieApi
        // $tvshowSearchArray = $betaserieApi->fetchTvShowId($tvshow->getTitle());

        // dump($tvshowSearchArray, $tvshowSearchArray['shows'][0]['id']);

        // $tvShowDataArray = $betaserieApi->fetchTvShowData(($tvshowSearchArray['shows'][0]['id']));

        // dump($tvShowDataArray);

        // Test du service omdbApi
        $tvShowData = $omdbApi->fetch($tvshow->getTitle());
        dump($tvShowData);

        // Si la série n'existe pas on affiche une 404
        if (!$tvshow) {
            throw $this->createNotFoundException()("La série $tvshow->getId n'existe pas");   
        }

        return $this->render('tv_show/show.html.twig', [
            'tvshow' => $tvshow,
        ]);
    }

    // /**
    //  * Méthode permettant d'afficher les détails d'une série avec le slug dans l'url
    //  * 
    //  * @Route("/{slug}", name="slug")
    //  *
    //  * @return Response
    //  */
    // public function showWithSlug(TvShow $tvshow): Response
    // {
    //     return $this->render('tv_show/show.html.twig', [
    //         'tvshow' => $tvshow
    //     ]);
    // }
}
