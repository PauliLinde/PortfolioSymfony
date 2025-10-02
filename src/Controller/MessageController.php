<?php

namespace App\Controller;

use App\Service\MessageService;
use App\Validator\MessageValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{

    #[Route('/newMessage', methods: ['POST'])]
    public function newMessage(Request $request, MessageService $messageService,
                                MessageValidator $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $validator->validateData($data);
            $response = $messageService->addToDatabase($data['name'], $data['email'], $data['message']);
            return new JsonResponse($response);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Server error'], 500);
        }
    }
}
