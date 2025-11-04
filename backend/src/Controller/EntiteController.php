<?php

namespace App\Controller;

use App\Entity\Entite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/entites')]
class EntiteController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $entites = $em->getRepository(Entite::class)->findAll();
        $data = array_map(function (Entite $e) {
            return [
                'id' => $e->getId(),
                'nom' => $e->getNom(),
                'direction' => $e->getDirection(),
                'responsable' => $e->getResponsable(),
                'abreviation' => $e->getAbreviation(),
            ];
        }, $entites);

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $payload = json_decode($request->getContent(), true) ?? [];
        if (!isset($payload['nom']) || trim((string)$payload['nom']) === '') {
            return $this->json(['error' => 'Le nom est requis'], 400);
        }

        $entite = new Entite();
        $entite->setNom(trim($payload['nom']));
        $entite->setDirection($payload['direction'] ?? null);
        $entite->setResponsable($payload['responsable'] ?? null);
        $entite->setAbreviation($payload['abreviation'] ?? null);

        $em->persist($entite);
        $em->flush();

        return $this->json([
            'id' => $entite->getId(),
            'nom' => $entite->getNom(),
            'direction' => $entite->getDirection(),
            'responsable' => $entite->getResponsable(),
            'abreviation' => $entite->getAbreviation(),
        ], 201);
    }
}


