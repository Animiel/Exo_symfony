<?php

namespace App\Controller;

use App\Entity\SessionFormation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $sessions = $doctrine->getRepository(SessionFormation::class)->findAll();
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }

    #[Route('/session/{id}', name: 'detail_session')]
    public function detail_session(SessionFormation $session) {

        return $this->render('session/detailSession.html.twig', [
            'session' => $session
        ]);
    }

    #[Route('/session/{id}/programme', name: 'programme_session')]
    public function programmeSession(SessionFormation $session) {

        return $this->render('session/programme.html.twig', [
            'session' => $session
        ]);
    }
}
