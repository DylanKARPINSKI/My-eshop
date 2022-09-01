<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'user_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('default_home');
        }

        #1 - Instanciation
        $user = new User();
        #2 - Création du formulaire + mécanisme d'auto-hydratation
        $form = $this->createForm(RegisterFormType::class, $user)->handleRequest($request);

        #4 - Au clic du bouton "Valider"
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new DateTime());
            $user->setUpdatedAt(new DateTime());

            #La propriété "roles" est un array (tableau) 
            $user->setRoles(['ROLE_USER']);

            #Nous devons resetter manuellement le password, car par défaut il n'est pas hashé. Pour cela il est bon d'utiliser une méthode de hashage.
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user, $form->get('password')->getData()
                    )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            #la méthode addFlash() permet d'ajouter des messages déstinés aux utilisateur. On pourra tous les afficher en front (avec Twig)
            $this->addFlash('success', 'Inscription réussie, bienvenue .. dans le monde des ténèbres :) ');

            return $this->redirectToRoute('default_home');
        }

        #3 - Rendu de la vue Twig, avec le formulaire
        return $this->render('register/form.html.twig', [
            'form' => $form->createVIew()
        ]);
    }
}
