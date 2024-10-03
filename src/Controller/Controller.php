<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Controller extends AbstractController
{
    private $manager;
    private $articleRepo;
    public function __construct(ManagerRegistry $manager, ArticleRepository $articleRepo)
    {
        $this->manager = $manager;
        $this->articleRepo = $articleRepo;
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $limit = 3;
        
        $articles = $this->articleRepo->findArticlesWithLimit($limit);
        return $this->render('/index.html.twig', [
            "articles" => $articles
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(Request $request): Response
    {
        
        return $this->render('/about.html.twig', [
        ]);
    }
}
