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
        // Skapa response med CORS headers
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', 'https://portfolio-svelte-kit-rose.vercel.app');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Max-Age', '3600');

        // Hantera OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            $response->setStatusCode(204);
            return $response;
        }

        $data = json_decode($request->getContent(), true);

        if(!$data || !isset($data['email']) || !isset($data['message'])){
            $response->setData(['error' => 'Invalid data']);
            $response->setStatusCode(400);
            return $response;
        }

        try {
            $message = new Message();
            $message->setEmail($data['email']);
            $message->setMessage($data['message']);

            $brevoService->sendEmail($data['email'], $data['message']);

            $entityManager->persist($message);
            $entityManager->flush();

            $response->setData(['success' => true, 'id' => $message->getId()]);
            return $response;
        } catch (\Exception $e) {
            $response->setData(['error' => $e->getMessage()]);
            $response->setStatusCode(500);
            return $response;
        }
    }
}
