<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    private $brevoService;
    private $entityManager;
    public function __construct(BrevoService $brevoService, EntityManagerInterface $entityManager){
        $this->brevoService = $brevoService;
        $this->entityManager = $entityManager;
    }

    public function addToDatabase($email, $message): array
    {
        try {
            $newMessage = new Message();
            $newMessage->setEmail($email);
            $newMessage->setMessage($message);

            $this->brevoService->sendEmail($email, $message);

            $this->entityManager->persist($newMessage);
            $this->entityManager->flush();

            return ['success' => true];
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to save message: ' . $e->getMessage());
        }
    }

}
