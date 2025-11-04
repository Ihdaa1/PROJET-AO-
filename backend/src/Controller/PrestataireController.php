<?php

namespace App\Controller;

use App\Entity\Prestataire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/prestataires')]
class PrestataireController extends AbstractController
{
    #[Route('', name: 'get_prestataires', methods: ['GET'])]
    public function getPrestataires(EntityManagerInterface $em): JsonResponse
    {
        try {
            $prestataires = $em->getRepository(Prestataire::class)->findAll();

            $data = array_map(fn($p) => [
                'id' => $p->getId(),
                'nom' => $p->getNom(),
                'email' => $p->getEmail(), 
                'telephone' => $p->getTelephone(),
            ], $prestataires);

            return $this->json($data);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la récupération des prestataires'], 500);
        }
    }

    #[Route('', name: 'add_prestataire', methods: ['POST'])]
    public function addPrestataire(
        Request $request, 
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data || !isset($data['nom'], $data['email'], $data['telephone'])) {
                return $this->json(['error' => 'Données incomplètes'], 400);
            }

            // Vérification si l'email existe déjà
            $existingPrestataire = $em->getRepository(Prestataire::class)
                ->findOneBy(['email' => $data['email']]);
            
            if ($existingPrestataire) {
                return $this->json(['error' => 'Cet email est déjà utilisé'], 409);
            }

            // Validation du format email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->json(['error' => 'Format d\'email invalide'], 400);
            }

            // Validation du format téléphone (exemple basique)
            if (!preg_match('/^[0-9+\-\s()]{8,}$/', $data['telephone'])) {
                return $this->json(['error' => 'Format de téléphone invalide'], 400);
            }

            $prestataire = new Prestataire();
            $prestataire->setNom(trim($data['nom']));
            $prestataire->setEmail(trim($data['email']));
            $prestataire->setTelephone(trim($data['telephone']));

            // Validation des contraintes de l'entité
            $errors = $validator->validate($prestataire);
            if (count($errors) > 0) {
                $errorMessages = array_map(fn($error) => $error->getMessage(), iterator_to_array($errors));
                return $this->json(['errors' => $errorMessages], 400);
            }

            $em->persist($prestataire);
            $em->flush();

            return $this->json([
                'message' => 'Prestataire ajouté avec succès',
                'prestataire' => [
                    'id' => $prestataire->getId(),
                    'nom' => $prestataire->getNom(),
                    'email' => $prestataire->getEmail(),
                    'telephone' => $prestataire->getTelephone(),
                ]
            ], 201);  // Code 201 Created

        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Erreur lors de l\'ajout du prestataire',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}