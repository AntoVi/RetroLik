<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'registration')]
    public function index(): Response
    {
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }


    #[Route('/inscription', name: 'game_inscription')]
    public function gameInscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        // Si getUser() renvoi quelque chose, cela veut dire que l'internaute est 
        // authentifié, et qu'il ne peut pas aller sur la page inscription on le redirige vers la route
        // profil'

        if($this->getUser())
        {
            return $this->redirectToRoute('game_profil');
        }

        $user = new User;

        $formInscription = $this->createForm(RegistrationFormType::class, $user, [
            'userRegister' => true // ceci est la condition ou on entre dans le formulaire RegistrationFormType
            // pour afficher un formulaire en particulier qui en contient plusieurs 
        ]);

        $formInscription->handleRequest($request);
        // dump($formInscription->isValid());

        if($formInscription->isSubmitted() && $formInscription->isValid())
        {
            // on fait appel à l'objet $userPasswordHasher de l'interface UserPasswordHasherInterface
            // ceci permet d'encrypter le mot de passe dans la BDD 
            $hash = $userPasswordHasher->hashPassword(
                $user,
                $formInscription->get('password')->getData()
            );

            $user->setPassword($hash);
            $user->setAvatar('image-profil.png');

            $this->addFlash('success', "Compte crée avec succès");

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/inscription.html.twig', [
            'formInscription' => $formInscription->createView()
        ]);
    }

    #[Route('/profil', name: 'game_profil')]
    public function gameProfil(): Response
    {

        // Si getUser() est null, et ne renvoi rien, cela veut dire que l'internaute n'est pas
        // authentifié et qu'il ne peut aller sur le profil , on le redirige vers la route
        // connexion 'login'
        if(!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }


        $user =$this->getUser();



        return $this->render('registration/profil.html.twig', [
            'user' => $user
        ]);
    }

    // Cette route nous conduira sur un nouveau formulaire pour pouvoir modifier les informations persos de son profil sauf le MDP
    #[Route('/profil/{id}/update', name: 'game_profil_update')]
    public function gameProfilUpdate(User $user, Request $request, EntityManagerInterface $manager,SluggerInterface $slugger ): Response 
    {

        if($user)
        {
            $photoActuelle = $user->getAvatar();
        }

        $formUpdate = $this->createForm(RegistrationFormType::class, $user, [
            'profilUpdate' => true 
        ]);

        $formUpdate->handleRequest($request);

        if($formUpdate->isSubmitted() && $formUpdate->isValid())
        {

            // DEBUT Traitement avatar

            $photo = $formUpdate->get('Avatar')->getData();

            if($photo)
            {
                $nomOriginePhoto = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);

                $secureNomPhoto = $slugger->slug($nomOriginePhoto);

                $nouveauNomFichier = $secureNomPhoto . '-' . uniqid() . '-' . $photo->guessExtension();

                try
                {
                    $photo->move(
                        $this->getParameter('photo_directory'),
                        $nouveauNomFichier
                     );
                }
                catch(FileException $e)
                {

                }
                $user->setAvatar($nouveauNomFichier);
            }
            else 
            {
                if(isset($photoActuelle))
                    $user->setAvatar($photoActuelle);
                else 

                    $user->setAvatar(null);
            }
             
            // fin Traitement avatar
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre profil a été modifié avec succès");

            return $this->redirectToRoute('game_profil');


        }

        
        return $this->render('registration/profil_update.html.twig', [
            'formUpdate' => $formUpdate->createView(),
            'photoActuelle' => $user->getAvatar()
        ]);


    }





}