<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\BienImmobilierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateAnnonceController extends AbstractController
{
    #[Route('/create/annonce', name: 'app_create_annonce')]
    public function index(BienImmobilierRepository $bienImmobilierRepository): Response
    {
        $bienImmobilier = $bienImmobilierRepository->find(1);

        $annonce = new Annonce();
        $annonce->setTitre('Annonce fictive');
        $annonce->setPrixM2Habitable('2000');
        $annonce->setDate(new \DateTime());
        $annonce->setBienImmobilier($bienImmobilier);

        return $this->render('create_annonce/index.html.twig', [
            'annonce' => $annonce,
        ]);
    }
}