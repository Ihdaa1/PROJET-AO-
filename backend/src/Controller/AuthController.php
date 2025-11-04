<?php
namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(private JwtService $jwtService) {}

    #[Route('/api/login', name:'api_login', methods:['POST'])]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?: [];
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $userRepository->findOneBy(['email'=>$email]);
        if (!$user || !$hasher->isPasswordValid($user, $password)) {
            return $this->json(['error'=>'Invalid credentials'], 401);
        }

        $token = $this->jwtService->generate([
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);

        return $this->json(['token'=>$token]);
    }

    #[Route('/api/login', name:'api_login_options', methods:['OPTIONS'])]
    public function loginOptions(): JsonResponse
    {
        return $this->json([], 200);
    }

    #[Route('/api/profile', name:'api_profile', methods:['GET'])]
    public function profile(Request $request, UserRepository $userRepository): JsonResponse
    {
        $auth = $request->headers->get('Authorization');
        if (!$auth || !str_starts_with($auth,'Bearer ')) return $this->json(['error'=>'No token'],401);
        $token = substr($auth,7);
        $payload = $this->jwtService->validate($token);
        if (!$payload) return $this->json(['error'=>'Invalid token'],401);

        $user = $userRepository->find($payload->sub);
        if (!$user) return $this->json(['error'=>'User not found'],404);

        return $this->json(['id'=>$user->getId(),'email'=>$user->getEmail(),'roles'=>$user->getRoles()]);
    }
}
