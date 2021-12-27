<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Articles;
use App\Entity\Category;
use App\Form\ArticlesType;
use App\Form\CategorieType;
use App\Form\JeuxType;
use App\Form\RegistrationFormType;
use App\Repository\JeuxRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BackOfficeController extends AbstractController
{
    #[Route('/admin', name: 'game_admin')]
    public function index(): Response
    {
        return $this->render('back_office/index_admin.html.twig');
    }

    #[Route('/admin/users', name: 'game_admin_user')]
    #[Route('/admin/users/{id}/remove', name: 'game_admin_user_remove')]
    public function adminUsers(UserRepository $repoUser, EntityManagerInterface $manager, User $useRemove = null): Response
    {
        // Selection du nom des champs/colonnes
        $table = $manager->getClassMetadata(User::class)->getFieldNames();

        $user = $repoUser->findAll();
       
        // Traitement suppression user en BDD 
        if($useRemove)
        {
            // Avant de supprimer l'user dans la bdd, on stock son ID afin de l'intégrer dans 
            // le message addflash une fois la suppression effectué 
            $id = $useRemove->getId();

            $manager->remove($useRemove);
            $manager->flush();

            $this->addFlash('success', "L'utilisateur $id a été supprimé avec succès");

            return $this->redirectToRoute('game_admin_user');
        }





        return $this->render('back_office/admin.user.html.twig', [
            'table' => $table,
            'user' => $user
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'game_admin_user_edit')]
    public function adminUsersEdit(User $user, Request $request, EntityManagerInterface $manager)
    {
        $formUserUpdate = $this->createForm(RegistrationFormType::class, $user , [
            'userBack' => true
        ]);

        $formUserUpdate->handleRequest($request);

        if($formUserUpdate->isSubmitted() && $formUserUpdate->isValid())
        {
           

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Le rôle a été modifié avec succès");

            return $this->redirectToRoute('game_admin_user');

        }

        return $this->render('back_office/admin.user.edit.html.twig', [
            'formUserUpdate' => $formUserUpdate->createView()
        ]);
    }

    #[Route('/admin/comments', name: 'game_admin_comment')]
    #[Route('/admin/comments/{id}/remove', name: 'game_admin_comment_remove')]
    public function adminComment(CommentRepository $repoComment,EntityManagerInterface $manager, Comment $comRemove = null)
    {

        $table = $manager->getClassMetadata(Comment::class)->getFieldNames();

        $comment = $repoComment->findAll();

        if($comRemove)
        {
            $id = $comRemove->getId();
            

            $manager->remove($comRemove);
            $manager->flush();

            $this->addFlash('success', "Le commentaire $id a été supprimé avec succès");

            return $this->redirectToRoute('game_admin_comment');


        }

        return $this->render('back_office/admin.comment.html.twig', [
            'table' => $table,
            'comment' => $comment
        ]);
    }

    #[Route('/admin/categories', name: 'game_admin_categorie')]
    #[Route('/admin/categories/{id}/remove', name: 'game_admin_categorie_remove')]
    public function adminCategorie(CategoryRepository $catRepo,EntityManagerInterface $manager, Category $catRemove = null )
    {

        $table = $manager->getClassMetadata(Category::class)->getFieldNames();

        $categorie = $catRepo->findALL();

        if($catRemove)
        {
            $id = $catRemove->getId();

            $manager->remove($catRemove);
            $manager->flush();


            $this->addFlash('info', "La catégorie $id a été supprimé avec succès");

            return $this->redirectToRoute('game_admin_categorie');

        }


        return $this->render('back_office/admin.categorie.html.twig', [
            
            'table' => $table,
            'categorie' => $categorie
        ]);
    }
    #[Route('/admin/categories/add', name: 'game_admin_categorie_add')]
    #[Route('/admin/categories/{id}/edit', name: 'game_admin_categorie_edit')]
    public function adminCategorieForm(Category $categorie = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$categorie)
        {
            $categorie = new Category;
        }

        $formCat = $this->createForm(CategorieType::class, $categorie);

        $formCat->handleRequest($request);

        if($formCat->isSubmitted() && $formCat->isValid())
        {
            if($categorie->getId())
                $texte = "Modifiée";
            else 
                $texte = "Ajoutée";
            

            $manager->persist($categorie);
            
            $manager->flush();

            $id = $categorie->getId();

            $this->addFlash('info', " la catégorie $id a été $texte avec succéss ");

            return $this->redirectToRoute('game_admin_categorie');
        }





        return $this->render('back_office/admin.categorie.form.html.twig', [
            'formCat' => $formCat->createview(),
            'editMode' => $categorie->getId()
        ]);
    }


    

    #[Route('/admin/articles', name: 'game_admin_article')]
    #[Route('/admin/articles{id}/remove', name: 'game_admin_article_remove')]
    public function adminArticle(ArticlesRepository $repoArti, EntityManagerInterface $manager, Articles $artRemove = null)
    {
        
       $table = $manager->getClassMetadata(Articles::class)->getFieldNames();

       $article = $repoArti->findALL();

       if($artRemove)
       {
           $id = $artRemove->getId();

           $manager->remove($artRemove);

           $manager->flush();

           $this->addFlash('info', "L'article $id a été supprimé avec succès");

           return $this->redirectToRoute('game_admin_article');
       }


        return $this->render('back_office/admin.article.html.twig', [
            'table' => $table,
            'article' => $article

        ]);
    }

    #[Route('/admin/articles/add', name: 'game_admin_article_add')]
    #[Route('/admin/articles/{id}/edit', name: 'game_admin_article_edit')]
    public function adminArticleEdit(Articles $articles = null, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        if(!$articles)
        {
            $articles = new Articles;
        }

        $formArt = $this->createForm(ArticlesType::class, $articles);

        $formArt->handleRequest($request);

        // dd($articles);

        if($formArt->isSubmitted() && $formArt->isValid())
        {
            $user = $this->getUser();

            if($articles->getId())
                $texte = "Modifié";
            else 
                $texte = "Ajouté";

            if(!$articles->getId())
            $articles->setDate(new \DateTime());

            $articles->setUser($this->getUser());

            $articles->setAuteur($user->getPrenom() . ' ' . $user->getNom());

            $manager->persist($articles);
            $manager->flush(); 

            $id = $articles->getId();

            $this->addFlash('info', "L'article' $id a été $texte avec succès !");

            return $this->redirectToRoute('game_admin_article');

        }



        return $this->render('back_office/admin.article.form.html.twig', [
            'formArt' => $formArt->createview(),
            'user' => $user,
            'article' => $articles,
            'editMode' => $articles->getId()
        ]);
    }

    #[Route('/admin/jeux', name: 'game_admin_jeux')]
    #[Route('/admin/jeux/{id}/delete', name: 'game_admin_jeux_delete')]
    public function adminJeux(JeuxRepository $jeuRepo,EntityManagerInterface $manager, Jeux $jeuDel = null)
    {

        $table = $manager->getClassMetadata(Jeux::class)->getFieldNames();

        $jeux = $jeuRepo->findAll();

        if($jeuDel)
        {
            $id = $jeuDel->getId();

            $manager->remove($jeuDel);

            $manager->flush();

            $this->addFlash('info', "Le jeu $id a été supprimé avec succès");

            return $this->redirectToRoute('game_admin_jeux');
        }


        return $this->render('back_office/admin.jeux.html.twig', [
            'table' => $table,
            'jeux' => $jeux
        ]);
    }

    #[Route('/admin/jeux/add', name: 'game_admin_jeux_add')]
    #[Route('/admin/jeux/{id}/edit', name: 'game_admin_jeux_edit')]
    public function adminJeuxCreate(Jeux $jeux = null, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger)
    {

        if($jeux)
        {
            $photoActuelle = $jeux->getImg();
        }

        if(!$jeux)
        {
            $jeux = new Jeux;
        }

        $formJeu = $this->createForm(JeuxType::class, $jeux);

        $formJeu->handleRequest($request);


        if($formJeu->isSubmitted() && $formJeu->isValid())
        {

            if(!$jeux->getId())
                $texte = "Ajouté";
            else
                $texte = "Modifié"; 
                

                // DEBUT Traitement DE LA PHOTO 
            $photo = $formJeu->get('Img')->getData();
                

            if($photo)  
            {
                
                $nomOriginePhoto = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            

                $secureNomPhoto = $slugger->slug($nomOriginePhoto);

        
                $nouveauNomFichier = $secureNomPhoto . '-' . uniqid() . '.' . $photo->guessExtension();

                

                try // try on tente de copier la photo dans le bon dossier
                {
                
                    $photo->move(
                        $this->getParameter('photo_directory'),
                        $nouveauNomFichier
                    );
                }
                catch(FileException $e)
                {

                }
                // on enregistre la photo en BDD
                $jeux->setImg($nouveauNomFichier);
            }
            else  
            {
                // Si l'article possède une photo mais qu'on ne souhaite pas la modifiée, 
                // alors on entre la condition IF et on renvoi la même photo dans la BDD
                
                if(isset($photoActuelle))
                    $jeux->setImg($photoActuelle);
               
            }

            // FIN TRAITEMENT PHOTO

            $manager->persist($jeux);

            $manager->flush();

            $id = $jeux->getId(); 

            $this->addFlash('info', "Le jeux $id a été $texte avec succès");

            return $this->redirectToRoute('game_admin_jeux');

        }

         


        return $this->render('back_office/admin.jeux.form.html.twig', [
            'formJeu' => $formJeu->createview(),
            'photoActuelle' => $jeux->getImg(),
            'editMode' => $jeux->getId()
            
        ]);
    }
}
