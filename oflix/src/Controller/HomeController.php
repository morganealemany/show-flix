<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController 
{

    /**
     * Méthode affichant la page d'accueil
     * 
     * @Route("/", name="home")
     *
     * @return Response
     */
    public function home(TvShowRepository $repositoryTvShow): Response
    {
        $lastTvShows = $repositoryTvShow->findBy([], ['id' => 'ASC'], 3);

        return $this->render('home/home.html.twig', [
            'lastTvShows' => $lastTvShows,
        ]);
    }


    /**
     * Méthode affichant la liste des séries
     * 
     * @Route("/tvshow/", name="tvshow")
     *
     * @return Response
     */
    public function tvShowList(TvShowRepository $repositoryTvShow): Response 
    {
        $list = $repositoryTvShow->findAll();

        return $this->render('tvShow/list.html.twig', [
            'list' => $list,
        ]);
    }

    /**
     * Méthode affichant la page d'une série
     * 
     * @Route("/tvshow/{id}", name="singletvshow", requirements={"id": "\d+"})
     *
     * @return Response
     */
    public function show($id, TvShowRepository $repositoryTvShow, SeasonRepository $repositorySeason): Response
    {
        // Récupérayion des infos de la série dont l'id est passée en argument
        $tvshow = $repositoryTvShow->find($id);

        // Récupération des saisons correspondantes à l'id de la série
        $seasons = $repositorySeason->findBy(['tvShow' => $id ]);
        return $this->render('tvShow/single.html.twig', [
            'tvshow' => $tvshow,
            'seasons' => $seasons,
        ]);
    }
}