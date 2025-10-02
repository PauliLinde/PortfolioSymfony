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
            error_log('Step 1: Received data - ' . json_encode($data));

            $validator->validateData($data);
            error_log('Step 2: Validation passed');

            $response = $messageService->addToDatabase($data['name'], $data['email'], $data['message']);
            error_log('Step 3: Database save successful');

            return new JsonResponse($response);
        } catch (\InvalidArgumentException $e) {
            error_log('Validation error: ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            error_log('Server error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return new JsonResponse(['error' => 'Server error: ' . $e->getMessage()], 500);
        }


        /*$data = json_decode($request->getContent(), true);

        try {
            $validator->validateData($data);
            $response = $messageService->addToDatabase($data['name'], $data['email'], $data['message']);
            return new JsonResponse($response);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Server error'], 500);
        }*/
    }
}
