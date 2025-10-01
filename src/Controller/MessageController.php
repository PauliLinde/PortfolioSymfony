<?php

namespace App\Controller;

use App\Entity\Message;
use App\Service\BrevoService;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{

    #[Route('/newMessage', methods: ['POST'])]
    public function newMessage(Request $request, MessageService $messageService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $response = $messageService->addToDatabase($data['email'], $data['message']);
        return new JsonResponse($response);
    }
}
