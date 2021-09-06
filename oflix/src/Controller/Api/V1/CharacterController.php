<?php

namespace App\Controller\Api\V1;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/characters", name="api_v1_character_")
 */
class CharacterController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CharacterRepository $characterRepository): Response
    {
        $characters = $characterRepository->findAll();

        return $this->json($characters, 200, [], [
            'groups' => 'character_list'
        ]);
    }
    /**
     * Permet d'afficher le détail d'un personnage en fonction de son id
     * 
     * @Route("/{id}", name="show", methods={"GET"})
     *
     * @return void
     */
    public function show(int $id, CharacterRepository $characterRepository)
    {
        $character = $characterRepository->find($id);
        if (!$character) {
            return $this->json([
                'error' => 'Le personnage ' . $id . ' n\'existe pas'
            ], 404);
        }

        return $this->json($character, 200, [], [
            'groups' => 'character_detail'
        ]);
    }

    /**
     * Permet de créer un nouveau personnage
     * 
     * @Route("/", name="add", methods={"POST"})
     *
     * @return void
     */
    public function add(Request $request, SerializerInterface $serialise) 
    {
        // On récupère le json
        $jsonData = $request->getContent();

        // On transforme le json en objet
        $newCharacter = $serialise->deserialize($jsonData, Character::class, 'json');

        //Puis on sauvegarde
        $em = $this->getDoctrine()->getManager();
        $em->persist($newCharacter);
        $em->flush();

        // On retourne une réponse au format json
        return $this->json([
            'message' => 'Le personnage ' . $newCharacter->getFirstname() . ' ' . $newCharacter->getLastname() . ' a bien été créé',
            'character' => $newCharacter
        ], 201);
    }

    /**
     * Permet de modifier un personnage
     * 
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH"})
     * @return void
     */
    public function edit(int $id, CharacterRepository $characterRepository, Request $request, SerializerInterface $serialise)
    {
        // Le personnage à modifier
        $character = $characterRepository->find($id);

        if (!$character) {
            return $this->json([
                'error' => 'Le personnage ' . $id . ' n\'existe pas'
            ], 404);
        }

        // Les modifications à apporter
        $jsonData = $request->getContent();
        // dd($character, $jsonData);

        // Transformation en json
        $editCharacter = $serialise->deserialize($jsonData, Character::class, 'json');

        // On remplit les propriétés
        if ($editCharacter->getFirstname()) {
            $character->setFirstname($editCharacter->getFirstname());
        }
        if ($editCharacter->getLastname()) {
            $character->setLastname($editCharacter->getLastname());
        }
        if ($editCharacter->getGender()) {
            $character->setGender($editCharacter->getGender());
        }

        // Sauvegarde en BDD
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        // Retour au format json
        return $this->json($character, 200);
    }

    /**
     * Permet de supprimer un personnage
     * 
     * @Route("/{id}", name="delete", methods={"DELETE"})
     *
     * @return void
     */
    public function delete(int $id, CharacterRepository $characterRepository)
    {
        $character = $characterRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($character);
        $em->flush();

        return $this->json($character, 204);
    }
}


