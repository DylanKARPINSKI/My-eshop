<?php

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home', methods: ['GET'])]
    public function home(EntityManagerInterface $entityManager):Response
    {
        $users= $entityManager->getRepository(User::class)->findAll();

        return $this->render('default/home.html.twig', [
            'users'=> $users
        ]);
    }
}
