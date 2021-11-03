<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** 
 * @Route("/character", name="character_")
 */
class CharacterController extends AbstractController
{
    /**
     * Méthode permettant l'affichage de la liste des personnages
     * 
     * @Route("/", name="index")
     */
    public function index(CharacterRepository $repositoryCharacter): Response
    {
        return $this->render('character/index.html.twig', [
            'charactersList' => $repositoryCharacter->findAll(),
        ]);
    }
}
