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

    public function addToDatabase($name, $email, $message): array
    {
        try {
            error_log('Creating new message entity');
            $newMessage = new Message();
            $newMessage->setName($name);
            $newMessage->setEmail($email);
            $newMessage->setMessage($message);

            error_log('Sending email via Brevo');
            $this->brevoService->sendEmail($name, $email, $message);

            error_log('Persisting to database');
            $this->entityManager->persist($newMessage);
            $this->entityManager->flush();

            return ['success' => true];
        } catch (\Exception $e) {
            error_log('MessageService error: ' . $e->getMessage());
            throw new \RuntimeException('Failed to save message: ' . $e->getMessage());
        }
        /*try {
            $newMessage = new Message();
            $newMessage->setName($name);
            $newMessage->setEmail($email);
            $newMessage->setMessage($message);

            $this->brevoService->sendEmail($name, $email, $message);

            $this->entityManager->persist($newMessage);
            $this->entityManager->flush();

            return ['success' => true];
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to save message: ' . $e->getMessage());
        }*/
    }

}
