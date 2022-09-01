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
        $produits= $entityManager->getRepository(User::class)->findBy(['deletedAt' => null]);

        return $this->render('default/home.html.twig', [
            'produits'=> $produits
        ]);
    }
}
