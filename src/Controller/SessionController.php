<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Entity\SessionFormation;
use App\Form\SessionFormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SessionFormationRepository;
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

    #[Route('session/add', name: 'add_session')]
    public function addSession(ManagerRegistry $doctrine, SessionFormation $session = null, Request $request) {
        if (!$session) {
            $session = new SessionFormation();
        }

        $form = $this->createForm(SessionFormationType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/add.html.twig', [
            'formSession' => $form->createView()
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

    #[Route('/session/{idSe}/log/{idSt}', name: 'log_stagiaire')]
    #[ParamConverter("session", options:["mapping" => ["idSe" => "id"]])]
    #[ParamConverter("stagiaire", options:["mapping" => ["idSt" => "id"]])]
    public function logStagiaire(ManagerRegistry $doctrine, Stagiaire $stagiaire, SessionFormation $session) {

        $session->addStagiaire($stagiaire);
        $em = $doctrine->getManager();
        $em->persist($session);
        $em->flush();
        return $this->redirectToRoute('show_nonInscrits', ['id' => $session->getId()]);
    }

    //quand plusieurs param --> utiliser Paramconverter pour bien faire le lien
    #[Route('/session/{idSe}/delete/{idSt}', name: 'delete_stagiaire')]
    #[ParamConverter("session", options:["mapping" => ["idSe" => "id"]])]
    #[ParamConverter("stagiaire", options:["mapping" => ["idSt" => "id"]])]
    public function deleteStagiaire(ManagerRegistry $doctrine, Stagiaire $stagiaire, SessionFormation $session) {

        $session->removeStagiaire($stagiaire);
        $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('show_nonInscrits', ['id' => $session->getId()]);
    }

    #[Route('/session/{idSe}/{idProg}/log', name: 'log_programme')]
    #[ParamConverter("session", options:["mapping" => ["idSe" => "id"]])]
    #[ParamConverter("programme", options:["mapping" => ["idProg" => "id"]])]
    public function logModule(ManagerRegistry $doctrine, Programme $programme, SessionFormation $session) {

        $session->addProgramme($programme);
        $em = $doctrine->getManager();
        $em->persist($session);
        $em->flush();
        return $this->redirectToRoute('show_nonInscrits', ['id' => $session->getId()]);
    }

    #[Route('/session/{idSe}/{idProg}/delete', name: 'delete_programme')]
    #[ParamConverter("session", options:["mapping" => ["idSe" => "id"]])]
    #[ParamConverter("programme", options:["mapping" => ["idProg" => "id"]])]
    public function deleteModule(ManagerRegistry $doctrine, Programme $programme, SessionFormation $session) {

        $session->removeProgramme($programme);
        $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('show_nonInscrits', ['id' => $session->getId()]);
    }

    #[Route('session/{id}/show', name: 'show_nonInscrits')]
    public function show(SessionFormation $session, SessionFormationRepository $sr) {
        $session_id = $session->getId();
        $nonInscrits = $sr->findNonInscrits($session_id);
        $nonProgrammes = $sr->findNonProgrammes($session_id);
        //$stagiaires = $str->findAll();
        //$programmes = $pr->findAll();

        return $this->render('/session/detailSession.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits,
            'nonProgrammes' => $nonProgrammes
        ]);
    }
}
