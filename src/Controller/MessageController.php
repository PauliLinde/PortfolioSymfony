<?php

namespace App\Controller;

use App\Entity\Message;
use App\Service\BrevoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{

    #[Route('/newMessage', methods: ['POST', 'OPTIONS'])]
    public function newMessage(Request $request, EntityManagerInterface $entityManager,
                               BrevoService $brevoService): JsonResponse
    {

        $allowedOrigins = [
            'http://localhost:5173',
            'http://localhost:5174'
        ];

        $origin = $request->headers->get('Origin');
        $allowOrigin = in_array($origin, $allowedOrigins) ? $origin : 'http://localhost:5173';

        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse(null, 200, [
                'Access-Control-Allow-Origin' => $allowOrigin,
                'Access-Control-Allow-Methods' => 'POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type',
            ]);
        }

        $data = json_decode($request->getContent(), true);

        if(!$data){
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $message = new Message();
        $message->setEmail($data['email']);
        $message->setMessage($data['message']);

        $brevoService->sendEmail($data['email'], $data['message']);

        $entityManager->persist($message);
        $entityManager->flush();
        return new JsonResponse(['success' => true, 'id' => $message->getId()]);
    }
}
