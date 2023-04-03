<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Entity\SessionFormation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    //quand un seul parametre --> lien automatique ente id et $session
    #[Route('/session/{id}', name: 'detail_session')]
    public function detail_session(SessionFormation $session) {

        return $this->render('session/detailSession.html.twig', [
            'session' => $session
        ]);
    }

    #[Route('/session/{id}/programme', name: 'programme_session')]
    public function programmeSession(SessionFormation $session) {

        return $this->render('session/detailSession.html.twig', [
            'session' => $session
        ]);
    }

    //quand plusieurs param --> utiliser Paramconverter pour bien faire le lien
    #[Route('/session/{idSe}/delete/{idSt}', name: 'delete_stagiaire')]
    #[ParamConverter("session", options:["mapping" => ["idSe" => "id"]])]
    #[ParamConverter("stagiaire", options:["mapping" => ["idSt" => "id"]])]
    public function deleteStagiaire(ManagerRegistry $doctrine, Stagiaire $stagiaire, SessionFormation $session) {

        $session->removeStagiaire($stagiaire);
        $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('detail_session', ['id' => $session->getId()]);
    }

    #[Route('/session/{idSe}/{idProg}/delete', name: 'delete_programme')]
    #[ParamConverter("session", options:["mapping" => ["idSe" => "id"]])]
    #[ParamConverter("programme", options:["mapping" => ["idProg" => "id"]])]
    public function deleteModule(ManagerRegistry $doctrine, Programme $programme, SessionFormation $session) {

        $session->removeProgramme($programme);
        $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('detail_session', ['id' => $session->getId()]);
    }
}
