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
