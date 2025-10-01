<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageService
{
    private $brevoService;
    private $entityManager;
    public function __construct(BrevoService $brevoService, EntityManagerInterface $entityManager){
        $this->brevoService = $brevoService;
        $this->entityManager = $entityManager;
    }
    public function addToDatabase($email, $message): JsonResponse
    {
        try {
            $newMessage = new Message();
            $newMessage->setEmail($email);
            $newMessage->setMessage($message);

            $this->brevoService->sendEmail($email, $message);

            $this->entityManager->persist($newMessage);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'id' => $message->getId()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

}
