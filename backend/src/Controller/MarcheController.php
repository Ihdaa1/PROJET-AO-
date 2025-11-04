<?php

namespace App\Controller;

use App\Entity\AppelOffre;
use App\Entity\Marche;
use App\Entity\Prestataire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/marches')]
class MarcheController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $aoId = (int)($data['refAO'] ?? 0);
        $prestId = (int)($data['refPrestataire'] ?? 0);
        $numero = $data['numero'] ?? null;
        $dateSig = $data['dateSignature'] ?? null;
        $montant = (string)($data['montantHT'] ?? '0');

        if (!$aoId || !$prestId || !$numero || !$dateSig) {
            return $this->json(['error' => 'refAO, refPrestataire, numero, dateSignature requis'], 400);
        }

        $ao = $em->getRepository(AppelOffre::class)->find($aoId);
        $prestataire = $em->getRepository(Prestataire::class)->find($prestId);
        if (!$ao || !$prestataire) {
            return $this->json(['error' => 'AO ou Prestataire introuvable'], 404);
        }

        $m = new Marche();
        $m->setRefAO($ao)
          ->setRefPrestataire($prestataire)
          ->setNumero($numero)
          ->setDateSignature(new \DateTime($dateSig))
          ->setMontantHT($montant);

        $em->persist($m);
        $em->flush();

        return $this->json([
            'id' => $m->getId(),
            'refAO' => $ao->getId(),
            'refPrestataire' => $prestataire->getId(),
            'numero' => $m->getNumero(),
            'dateSignature' => $m->getDateSignature()->format('Y-m-d'),
            'montantHT' => $m->getMontantHT(),
        ], 201);
    }

    #[Route('', methods: ['GET'])]
    public function list(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $aoId = (int)($request->query->get('ao') ?? 0);
        $repo = $em->getRepository(Marche::class);
        $marches = $aoId ? $repo->findBy(['refAO' => $aoId]) : $repo->findAll();

        $data = array_map(function (Marche $m) {
            return [
                'id' => $m->getId(),
                'refAO' => $m->getRefAO()->getId(),
                'refPrestataire' => $m->getRefPrestataire()->getId(),
                'numero' => $m->getNumero(),
                'dateSignature' => $m->getDateSignature()->format('Y-m-d'),
                'montantHT' => $m->getMontantHT(),
            ];
        }, $marches);

        return $this->json($data);
    }
}


