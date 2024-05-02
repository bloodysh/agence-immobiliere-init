<?php

namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Entity\Piece;
use App\Entity\User;
use App\Entity\TypePiece;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataImportController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/import/user', name: 'app_user_import')]
    public function import(): Response
    {
        $data = file_get_contents($this->getParameter('kernel.project_dir').'/public/users.json');
        $users = json_decode($data, true);

        $factory = new \RandomLib\Factory;
        $generator = $factory->getMediumStrengthGenerator();

        $tableauNomPassword = [];

        foreach ($users as $userData) {
            $user = new User();
            $user->setNom($userData['nom']);
            $user->setEmail($userData['email']);
            $user->setTel($userData['tel']);
            $user->setCarteAgentImmo($userData['carteAgentImmo']);


            $randomPassword = $generator->generateString(10);
            $user->setPassword($randomPassword);

            $tableauNomPassword[$userData['nom']] = $randomPassword;

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        return $this->render('data_import/index.html.twig', [
            'tableauNomPassword' => $tableauNomPassword,
        ]);

    }
    #[Route('/import/bienimmo', name: 'app_bienimmo_import')]
    public function importbienimmo(): Response
    {
        $data = file_get_contents($this->getParameter('kernel.project_dir').'/public/biensimmo.json');
        $biens = json_decode($data, true);

        foreach ($biens as $bienData) {
            $bien = new BienImmobilier();
            $bien->setLeUser($this->entityManager->getRepository(User::class)->find($bienData['user_id']));
            $bien->setRue($bienData['rue']);
            $bien->setVille($bienData['ville']);
            $bien->setCodePostal($bienData['code_postal']);

            $this->entityManager->persist($bien);
        }

        $this->entityManager->flush();

        return new Response('Biens immobiliers imported successfully');
    }

    #[Route('/import/pieces', name: 'app_pieces_import')]
    public function importpieces(): Response
    {
        $data = file_get_contents($this->getParameter('kernel.project_dir').'/public/pieces.json');
        $pieces = json_decode($data, true);

        foreach ($pieces as $pieceData) {
            $piece = new Piece();
            $piece->setBienImmobilier($this->entityManager->getRepository(BienImmobilier::class)->find($pieceData['bien_immobilier_id']));
            $piece->setTypePiece($this->entityManager->getRepository(TypePiece::class)->find($pieceData['type_piece_id']));
            $piece->setSurface($pieceData['surface']);

            $this->entityManager->persist($piece);
        }

        $this->entityManager->flush();

        return new Response('Pieces imported successfully');
    }
}