<?php

namespace App\Controller;

use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController 
{

    /**
     * Méthode affichant la page d'accueil
     * 
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index(TvShowRepository $repositoryTvShow): Response
    {
        $lastTvShows = $repositoryTvShow->findBy([], ['id' => 'DESC'], 3);

        return $this->render('home/index.html.twig', [
            'lastTvShows' => $lastTvShows,
        ]);
    }
}