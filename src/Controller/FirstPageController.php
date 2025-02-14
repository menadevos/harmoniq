<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FirstPageController extends AbstractController
{
    #[Route('/first/page', name: 'app_first_page')]
    public function index(): Response
    {
        return $this->render('first_page/index.html.twig', [
            'title' => 'Welcome to Harmoniq',
        ]);
    }
    }

