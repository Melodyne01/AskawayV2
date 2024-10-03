<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\AddUserType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersController extends AbstractController
{
    private $manager;
    private $articleRepo;
    private $passwordHasher;
    private $authenticationUtils;
    private $userRepo;

    public function __construct(ManagerRegistry $manager, ArticleRepository $articleRepo, UserPasswordHasherInterface $passwordHasher, AuthenticationUtils $authenticationUtils, UserRepository $userRepo)
    {
        $this->manager = $manager;
        $this->articleRepo = $articleRepo;
        $this->passwordHasher = $passwordHasher;
        $this->authenticationUtils = $authenticationUtils;
        $this->userRepo = $userRepo;
    }
    
    #[Route('/admin/register', name: 'register')]
    public function register(Request $request): Response
    {
        //Creation d'un nouvel objet User
        $user = new User();
        //Creation du formulaire sur base d'un formulaire crée au préalable 
        $form = $this->createForm(AddUserType::class, $user);

        $form->handleRequest($request);
        //Vérification de la conformité des données entrées par l'utilisateur
        if ($form->isSubmitted() && $form->isValid()) {
            //Chiffrement du mot de passe selon l'algorytme Bcrypt
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setAccountType("ROLE_USER");
            $this->manager->getManager()->persist($user);
            //Envoie des données vers la base de données
            $this->manager->getManager()->flush();

            $this->addFlash("success", "Le compte à bien été créé");
            return $this->redirectToRoute('login');
        }

        return $this->render('users/add.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/login', name: 'login')]
    public function index(ManagerRegistry $manager): Response
    {
        $userList = $this->userRepo->findAll();
        //S'il n'y a pas d'users
        if($userList == null){
            $user = new User();
            $user->setFirstname("Tanguy");
            $user->setLastname("Baldewyns");  
            $user->setEmail("tanguy.baldewyns@gmail.com");
            $hashedPassword = $this->passwordHasher->hashPassword($user,"aaaaaa");
            $user->setPassword($hashedPassword);
            $user->setAccountType("ROLE_ADMIN");
            $manager->getManager()->persist($user);
            $manager->getManager()->flush(); 
        }
        if ($this->getUser() != null){
            return $this->redirectToRoute('userprofile', [
            'id' => $this->getUser()->getUserIdentifier()
        ]);
        }
        $error = $this->authenticationUtils->getLastAuthenticationError();
        return $this->render('users/login.html.twig', [
            "error" => $error
        ]);
    }

    #[Route('user/userprofile/{id}', name: 'userprofile')]
    public function userprofile(User $user): Response
    {
        if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN"){
            return $this->redirectToRoute('admin');
        }
        if (!$this->getUser() || $this->getUser()->getUserIdentifier() != $user->getId()) {
            throw $this->createAccessDeniedException();
        }
        
        return $this->render('/users/userprofile.html.twig', [
            'user' => $user
        ]);
    }


    #[Route('/autoRedirect', name: 'autoRedirect')]
    public function autoRedirect()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('error404');
        }
        
        return $this->redirectToRoute('userprofile', [
            'id' => $this->getUser()->getUserIdentifier()
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }

    #[Route('/error404', name: 'error404')]
    public function error404()
    {
        return $this->render('error/error404.html.twig');
    }

}
