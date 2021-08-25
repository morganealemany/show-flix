<?php

namespace App\Controller\Backoffice;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/category", name="backoffice_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $repositoryCategory): Response
    {
        return $this->render('backoffice/category/index.html.twig', [
            'categories' => $repositoryCategory->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"})
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id, CategoryRepository $repositoryCategory): Response
    {
        $category = $repositoryCategory->find($id);

        if (!$category) {
            throw $this->createNotFoundException("La catégorie dont l'id est $id n'existe pas");
        }
        return $this->render('backoffice/category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
