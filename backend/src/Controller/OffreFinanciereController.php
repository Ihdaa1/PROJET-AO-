<?php

namespace App\Controller;

use App\Entity\AppelOffre;
use App\Entity\OffreFinanciere;
use App\Entity\Prestataire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/offres')]
class OffreFinanciereController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $aoId = (int)($data['refAO'] ?? 0);
        $prestId = (int)($data['refPrestataire'] ?? 0);

        if (!$aoId || !$prestId) {
            return $this->json(['error' => 'refAO et refPrestataire requis'], 400);
        }

        $ao = $em->getRepository(AppelOffre::class)->find($aoId);
        $prestataire = $em->getRepository(Prestataire::class)->find($prestId);
        if (!$ao || !$prestataire) {
            return $this->json(['error' => 'AO ou Prestataire introuvable'], 404);
        }

        $offre = new OffreFinanciere();
        $offre->setRefAO($ao);
        $offre->setRefPrestataire($prestataire);
        $em->persist($offre);
        $em->flush();

        return $this->json([
            'id' => $offre->getId(),
            'refAO' => $ao->getId(),
            'refPrestataire' => $prestataire->getId(),
            'totalHT' => $offre->getTotalHT(),
        ], 201);
    }

    #[Route('', methods: ['GET'])]
    public function list(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $aoId = (int)($request->query->get('ao') ?? 0);
        $repo = $em->getRepository(OffreFinanciere::class);
        $offres = $aoId ? $repo->findBy(['refAO' => $aoId]) : $repo->findAll();

        $data = array_map(function (OffreFinanciere $o) {
            return [
                'id' => $o->getId(),
                'refAO' => $o->getRefAO()->getId(),
                'refPrestataire' => $o->getRefPrestataire()->getId(),
                'totalHT' => $o->getTotalHT(),
            ];
        }, $offres);

        return $this->json($data);
    }

    #[Route('{id}', methods: ['GET'])]
    public function detail(int $id, EntityManagerInterface $em): JsonResponse
    {
        $offre = $em->getRepository(OffreFinanciere::class)->find($id);
        if (!$offre) return $this->json(['error' => 'Offre introuvable'], 404);

        $prix = [];
        foreach ($offre->getPrixLignes() as $p) {
            $prix[] = [
                'id' => $p->getId(),
                'designation' => $p->getDesignation(),
                'unite' => $p->getUnite()->getType(),
                'prixUnitaire' => $p->getPrixUnitaire(),
                'quantite' => $p->getQuantite(),
                'montantHT' => $p->getMontantHT(),
            ];
        }

        return $this->json([
            'id' => $offre->getId(),
            'refAO' => $offre->getRefAO()->getId(),
            'refPrestataire' => $offre->getRefPrestataire()->getId(),
            'totalHT' => $offre->getTotalHT(),
            'prix' => $prix,
        ]);
    }
}


