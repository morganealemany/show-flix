<?php

namespace App\Controller\Backoffice;

use App\Repository\CategoryRepository;
use App\Repository\CharacterRepository;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeController extends AbstractController
{
    /**
     * @Route("/backoffice", name="backoffice")
     */
    public function index(CharacterRepository $repositoryCharacter, CategoryRepository $repositoryCategory, TvShowRepository $repositoryTvShow): Response
    {
        return $this->render('backoffice/index.html.twig', [
            'characters' => $repositoryCharacter->findAll(),
            'categories' => $repositoryCategory->findAll(),
            'tvShows' => $repositoryTvShow->findAll(),

        ]);
    }
}
