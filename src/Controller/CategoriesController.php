<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\AddCategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController
{
    private $manager;
    private $articleRepo;
    private $categoryRepo;
    public function __construct(ManagerRegistry $manager, ArticleRepository $articleRepo, CategoryRepository $categoryRepo)
    {
        $this->manager = $manager;
        $this->articleRepo = $articleRepo;
        $this->categoryRepo = $categoryRepo;
    }
    
    #[Route('/categories', name: 'app_categories')]
    public function index(): Response
    {
        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }

    #[Route('/categories/create', name: 'app_category_create')]
    public function category_create(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(AddCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->getManager()->persist($category);
            $this->manager->getManager()->flush();

            return $this->redirectToRoute('app_admin',[
                'id' => $this->getUser()->getUserIdentifier()
            ]);
        }
        return $this->render('categories/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
