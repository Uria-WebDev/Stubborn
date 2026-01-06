<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController extends AbstractController
{
    // Route de déconection de l'utilisateur
    #[Route('/logout', name: 'app_logout')]
    public function index(): Response
    {
        return $this->render('logout/index.html.twig', [
            'controller_name' => 'LogoutController',
        ]);
    }
}
