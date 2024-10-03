<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{

    private ArticleRepository $articleRepo;
    private CategoryRepository $categoryRepo;
    public function __construct(ArticleRepository $articleRepo, CategoryRepository $categoryRepo)
    {
        $this->articleRepo = $articleRepo;
        $this->categoryRepo = $categoryRepo;
    }
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            "articles" => $this->articleRepo->findAll(),
            "categories" => $this->categoryRepo->findAll()
        ]);
    }
}
