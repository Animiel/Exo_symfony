<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $stagiaires = $doctrine->getRepository(Stagiaire::class)->findAll();
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

    
    #[Route('/stagiaire/add', name: 'add_stagiaire')]
    public function addStagiaire(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, Request $request) {
        
        if (!$stagiaire) {
            $stagiaire = new Stagiaire();
        }
        
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $stagiaire = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($stagiaire);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_stagiaire');
        }
        
        return $this->render('stagiaire/add.html.twig', [
            'formStagiaire' => $form->createView(),
        ]);
    }
    
    //toujours en fin pour éviter les conflits (ex: id = add => inexistant)
    #[Route('/stagiaire/{id}', name: 'profil_stagiaire')]
    public function profilStagiaire(Stagiaire $stagiaire) {
        return $this->render('stagiaire/profilStagiaire.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }
}
