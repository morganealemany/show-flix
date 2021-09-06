<?php

namespace App\Controller\Api\V1;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/categories", name="api_v1_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * URL : /api/v1/categories/
     * Route : api_v1_category_index
     * 
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        // On récupère les catégories stockées en BDD
        $categories = $categoryRepository->findAll();
        // dd($categories);
        // On retourne la liste au format JSON
        return $this->json($categories, 200, [], [
            // Cette entrée va permettre au serializer de transformer les objets en JSON, en allant chercher uniquement les propriétés taggées avec les nom 'categories'
            'groups' => 'category_list'
        ]);
    }

    /**
     * Permet d'afficher le détail d'une catégorie en fonction de son id
     * 
     * @Route("/{id}", name="show", methods={"GET"})
     *
     * @return void
     */
    public function show(int $id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            return $this->json([
                'error' => 'La catégorie ' . $id . ' n\'existe pas'
            ], 404);
        }

        return $this->json($category, 200, [], [
            'groups' => 'category_detail'
        ]);
    }

    /**
     * Permet de créer une nouvelle catégorie
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
        $newCategory = $serialise->deserialize($jsonData, Category::class, 'json');

        //Puis on sauvegarde
        $em = $this->getDoctrine()->getManager();
        $em->persist($newCategory);
        $em->flush();

        // On retourne une réponse au format json
        return $this->json($newCategory, 201);
    }

    /**
     * Permet de modifier une catégorie
     * 
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH"})
     * @return void
     */
    public function edit(int $id, CategoryRepository $categoryRepository, Request $request, SerializerInterface $serialise)
    {
        // La catégorie à modifier
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->json([
                'error' => 'La catégorie ' . $id . ' n\'existe pas'
            ], 404);
        }

        // Les modifications à apporter
        $jsonData = $request->getContent();
        // dd($category, $jsonData);

        // Transformation en json
        $editCategory = $serialise->deserialize($jsonData, Category::class, 'json');

        // On remplit les propriétés
        if ($editCategory->getName()) {
            $category->setName($editCategory->getName());
        }
        
        // Sauvegarde en BDD
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        // Retour au format json
        return $this->json($category, 200);
    }

    /**
     * Permet de supprimer une catégorie
     * 
     * @Route("/{id}", name="delete", methods={"DELETE"})
     *
     * @return void
     */
    public function delete(int $id, CategoryRepository$categoryRepository)
    {
        $category = $categoryRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->json($category, 204);
    }
}
