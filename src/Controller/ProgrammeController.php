<?php

namespace App\Controller;

use App\Entity\Programme;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme/{id}', name: 'app_programme')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $liste = $doctrine->getRepository(Programme::class)->findAll();
        return $this->render('programme/index.html.twig', [
            'liste' => $liste,
        ]);
    }
}
