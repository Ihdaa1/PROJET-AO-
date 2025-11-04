<?php

namespace App\Controller;

use App\Entity\AppelOffre;
use App\Entity\Entite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/appel-offres')]
class AOController extends AbstractController
{
    #[Route('', name: 'get_appel_offres', methods: ['GET'])]
    public function getAppelOffres(EntityManagerInterface $em): JsonResponse
    {
        try {
            // Test avec requête SQL directe pour voir si ça fonctionne
            $conn = $em->getConnection();
            $sql = "SELECT * FROM appel_offre ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery();
            $rows = $result->fetchAllAssociative();
            
            $data = [];
            foreach ($rows as $row) {
                $data[] = [
                    'id' => $row['id'],
                    'numeroAO' => $row['numero_ao'],
                    'datePublication' => $row['date_publication'],
                    'objet' => $row['objet'],
                    'entite' => $row['entite'],
                    'responsable' => $row['responsable'],
                    'designation' => $row['designation'],
                    'unite' => $row['unite'],
                    'prixHT' => $row['prix_ht'],
                    'quantite' => $row['quantite'],
                    'createdAt' => $row['created_at'],
                ];
            }
            
            return $this->json($data);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => 'Erreur lors de la récupération des appels d\'offres',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    #[Route('', name: 'add_appel_offre', methods: ['POST'])]
    public function addAppelOffre(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data || !isset($data['numeroAO'], $data['datePublication'], $data['objet'], $data['entiteId'])) {
                return $this->json(['error' => 'Données incomplètes (numeroAO, datePublication, objet, entiteId requis)'], 400);
            }

            $appelOffre = new AppelOffre();
            $appelOffre->setNumeroAO(trim($data['numeroAO']));
            $appelOffre->setDatePublication(new \DateTime($data['datePublication']));
            $appelOffre->setObjet(trim($data['objet']));
            // nouvelle logique: l'entité vient de entiteId
            $entite = $em->getRepository(Entite::class)->find((int)$data['entiteId']);
            if (!$entite) {
                return $this->json(['error' => 'Entité introuvable'], 404);
            }
            $appelOffre->setEntiteEntity($entite);
            // compat: garder aussi les champs string
            $appelOffre->setEntite($entite->getNom() ?? '');
            $appelOffre->setResponsable($entite->getResponsable() ?? '');
            $appelOffre->setDesignation($data['designation'] ?? null);
            $appelOffre->setUnite($data['unite'] ?? null);
            
            if (isset($data['prixHT']) && $data['prixHT'] !== '' && $data['prixHT'] !== null) {
                $appelOffre->setPrixHT((string)$data['prixHT']);
            } else {
                $appelOffre->setPrixHT(null);
            }
            
            if (isset($data['quantite']) && $data['quantite'] !== '' && $data['quantite'] !== null) {
                $appelOffre->setQuantite((int)$data['quantite']);
            } else {
                $appelOffre->setQuantite(null);
            }

            $errors = $validator->validate($appelOffre);
            if (count($errors) > 0) {
                $errorMessages = array_map(fn($error) => $error->getMessage(), iterator_to_array($errors));
                return $this->json(['errors' => $errorMessages], 400);
            }

            $em->persist($appelOffre);
            $em->flush();

            return $this->json([
                'message' => 'Appel d\'offre ajouté avec succès',
                'appelOffre' => [
                    'id' => $appelOffre->getId(),
                    'numeroAO' => $appelOffre->getNumeroAO(),
                    'datePublication' => $appelOffre->getDatePublication()->format('Y-m-d'),
                    'objet' => $appelOffre->getObjet(),
                    'entite' => $appelOffre->getEntite(),
                    'entiteId' => $entite->getId(),
                    'responsable' => $appelOffre->getResponsable(),
                    'designation' => $appelOffre->getDesignation(),
                    'unite' => $appelOffre->getUnite(),
                    'prixHT' => $appelOffre->getPrixHT(),
                    'quantite' => $appelOffre->getQuantite(),
                ]
            ], 201);

        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Erreur lors de l\'ajout de l\'appel d\'offre',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}