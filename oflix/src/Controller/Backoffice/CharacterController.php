<?php

namespace App\Controller\Backoffice;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/character", name="backoffice_character_")
 */
class CharacterController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CharacterRepository $repositoryCharacter): Response
    {
        return $this->render('backoffice/character/index.html.twig', [
            'characters' => $repositoryCharacter->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"})
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id, CharacterRepository $repositoryCharacter): Response
    {
        $character = $repositoryCharacter->find($id);

        if (!$character) {
            throw $this->createNotFoundException("Le personnage dont l'id est $id n'existe pas");
        }
        return $this->render('backoffice/character/show.html.twig', [
            'character' => $character,
        ]);
    }
}
