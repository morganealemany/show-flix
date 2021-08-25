<?php

namespace App\Controller\Backoffice;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Form\CharacterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * Permet de créer un nouveau personnage
     * 
     * @Route("/add", name="add")
     *
     * @return void
     */
    public function add(Request $request) 
    {
        // 1) On instancie un objet vide 
        $character = new Character();

        // 2) On instancie le formtype et on le lie à notre instance $character vide
        $form = $this->createForm(CharacterType::class, $character);

        // 4) On récupére les données issues du formulaire et on les injecte dans l'objet $character
        $form->handleRequest($request);

        // 5) On vérifie qu'on est bien dans le cas de la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // On créé le nouveau personnage
            // en appelant le manager de doctrine
            $em = $this->getDoctrine()->getManager();
            // on fait un "commit" de notre nouveau personnage
            $em->persist($character);

            // puis on fait un "push" pour la sauvegarder en BDD
            $em->flush();

            // On ajoute un message flash pour l'UX
            $this->addFlash('success', 'Le personnage ' . $character->getFirstname(). ' ' .$character->getLastname() .' a bien été créée');

            // On redirige la page vers l'index des personnages
            return $this->redirectToRoute('backoffice_character_index');
        }

        // 3) On retourne le formulaire pour qu'il puisse s'afficher dans la vue
        return $this->render('backoffice/character/add.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    /**
     * Permet d'éditer une catégorie
     * 
     * @Route("/{id}/edit", name="edit")
     *
     * @param integer $id
     * 
     * @return void
     */
    public function edit(int $id, Request $request, CharacterRepository $repositoryCharacter)
    {
        // 1) On récupére l'id de la catégorie à modifier
        $character = $repositoryCharacter->find($id);

        // 2) On instancie le formtype et on lie l'instance $character à notre formulaire
        $form = $this->createForm(CharacterType::class, $character);

        // 4) On réceptionne les données issues du formulaire et on les injecte dans l'objet $character
        $form->handleRequest($request);

        // 5) On vérifie qu'on est bien dans le cas d'une soumission de formulaire
        if ($form->isSubmitted()) {
            // On met à jour la catégorie
            // en appelant le manager de doctrine
            $em = $this->getDoctrine()->getManager();
            // persist n'est pas nécessaire lors d'une MAJ
            $em->flush();
        
            // Message flash
            $this->addFlash('success', 'Le personnage a bien été mis à jour');

            // Redirection sur la page du personnage
            return $this->redirectToRoute('backoffice_character_show', [
                'id' => $id,
            ]);
        
        }

        // 3) On affiche le formulaire dans la vue
        return $this->render('backoffice/character/edit.html.twig', [
            'formView' => $form->createView(),
            'character' => $character,
        ]);

    }
}

