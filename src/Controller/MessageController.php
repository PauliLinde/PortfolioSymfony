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

    #[Route('/newMessage', methods: ['POST'])]
    public function newMessage(Request $request, EntityManagerInterface $entityManager,
                                BrevoService $brevoService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!$data || !isset($data['email']) || !isset($data['message'])){
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        try {
            $message = new Message();
            $message->setEmail($data['email']);
            $message->setMessage($data['message']);

            $brevoService->sendEmail($data['email'], $data['message']);

            $entityManager->persist($message);
            $entityManager->flush();

            return new JsonResponse(['success' => true, 'id' => $message->getId()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
