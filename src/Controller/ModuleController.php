<?php

namespace App\Controller;

use App\Entity\ModuleFormation;
use App\Form\ModuleFormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(ManagerRegistry $doctrine, ModuleFormation $module = null, Request $request): Response
    {
        if (!$module) {
            $module = new ModuleFormation();
        }

        $form = $this->createForm(ModuleFormationType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation');
        }
        
        return $this->render('module/index.html.twig', [
            'formModule' => $form->createView(),
        ]);
    }
}
