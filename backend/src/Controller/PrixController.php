<?php

namespace App\Controller;

use App\Entity\OffreFinanciere;
use App\Entity\Prix;
use App\Entity\Unite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/offres')]
class PrixController extends AbstractController
{
    #[Route('{id}/prix', methods: ['GET'])]
    public function list(int $id, EntityManagerInterface $em): JsonResponse
    {
        $offre = $em->getRepository(OffreFinanciere::class)->find($id);
        if (!$offre) return $this->json(['error' => 'Offre introuvable'], 404);

        $rows = [];
        foreach ($offre->getPrixLignes() as $p) {
            $rows[] = [
                'id' => $p->getId(),
                'designation' => $p->getDesignation(),
                'unite' => $p->getUnite()->getType(),
                'prixUnitaire' => $p->getPrixUnitaire(),
                'quantite' => $p->getQuantite(),
                'montantHT' => $p->getMontantHT(),
            ];
        }
        return $this->json($rows);
    }

    #[Route('{id}/prix', methods: ['POST'])]
    public function add(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $offre = $em->getRepository(OffreFinanciere::class)->find($id);
        if (!$offre) return $this->json(['error' => 'Offre introuvable'], 404);

        $payload = json_decode($request->getContent(), true) ?? [];
        $items = isset($payload[0]) ? $payload : [$payload];

        foreach ($items as $item) {
            if (!isset($item['designation'], $item['uniteId'], $item['prixUnitaire'], $item['quantite'])) {
                return $this->json(['error' => 'designation, uniteId, prixUnitaire, quantite requis'], 400);
            }
            $unite = $em->getRepository(Unite::class)->find((int)$item['uniteId']);
            if (!$unite) return $this->json(['error' => 'Unité introuvable'], 404);

            $p = new Prix();
            $p->setOffre($offre)
              ->setDesignation($item['designation'])
              ->setUnite($unite)
              ->setPrixUnitaire((string)$item['prixUnitaire'])
              ->setQuantite((int)$item['quantite']);
            $em->persist($p);
        }

        // flush and update totals
        $em->flush();

        // recalcule total de l'offre
        $offre->recalculateTotalFromLines();
        $em->persist($offre);
        $em->flush();

        return $this->json(['message' => 'Lignes ajoutées', 'totalHT' => $offre->getTotalHT()], 201);
    }
}


