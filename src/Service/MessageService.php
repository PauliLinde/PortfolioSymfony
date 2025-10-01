<?php

namespace App\Service;

use App\Entity\Message;
use App\Validator\MessageValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageService
{
    private $brevoService;
    private $entityManager;
    private $validator;
    public function __construct(BrevoService $brevoService, EntityManagerInterface $entityManager,
    MessageValidator $validator){
        $this->brevoService = $brevoService;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function addToDatabase($email, $message): JsonResponse
    {
        $emailError = $this->validator->validateEmail($email);
        if($emailError){
            return new JsonResponse($emailError);
        }
        $messageError = $this->validator->validateMessage($message);
        if($messageError){
            return new JsonResponse($messageError);
        }

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
