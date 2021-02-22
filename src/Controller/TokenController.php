<?php

namespace App\Controller;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Users\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TokenController extends AbstractController
{
    /**
     * @Route("/api/token_check", name="token_check", methods={"POST"})
     * @param NormalizerInterface $normalizer
     * @param JWTTokenManagerInterface $jwt
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function tokenCheck(NormalizerInterface $normalizer, JWTTokenManagerInterface $jwt)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Unauthorised'
            ], 401);
        }
        $user = $this->getUser();
        $token = $jwt->create($user);
        $user->token = $token;
        $user = $normalizer->normalize($user, 'jsonld', ['groups' => ["user:auth"]]);
        
        
        
        return $this->json($user);
    }
}
