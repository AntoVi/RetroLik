<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentaireType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('game/home.html.twig', [
            'title' => 'Bienvenue sur GamesProject',
        ]);
    }

    #[Route('/categorie', name: 'game_categorie')]
    public function gameCategorie(CategoryRepository $repoCateogry): Response
    {


        $categorie = $repoCateogry->findAll();

        // dd($categorie);

        return $this->render('game/categorie.html.twig', [
            'categorie' => $categorie
        ]);
    }

    #[Route('/jeux', name: 'game_jeux')]
    public function gameJeux(): Response 
    {

        return $this->render('game/jeux.html.twig',[

        ]);
    }

    #[Route('/snake', name: 'game_snake')]
    public function gameSnake(CategoryRepository $repoCateogry): Response
    {


        

        // dd($categorie);

        return $this->render('game/snake.html.twig', [
          
        ]);

    }

    #[Route('/pacman', name: 'game_pacman')]
    public function gamePacMan(CategoryRepository $repoCateogry): Response
    {


        

        // dd($categorie);

        return $this->render('game/pacman.html.twig', [
          
        ]);
    }

    #[Route('/tictac', name: 'game_tictac')]
    public function gameTicTacToe(CategoryRepository $repoCateogry): Response
    {


        

        // dd($categorie);

        return $this->render('game/tictactoe.html.twig', [
          
        ]);
    }

    
 


    
}
