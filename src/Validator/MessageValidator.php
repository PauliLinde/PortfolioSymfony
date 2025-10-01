<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\JsonResponse;

class MessageValidator
{
    public function validateName($name): ?JsonResponse
    {
        if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            return new JsonResponse(['error' => 'Only letters and white space allowed'], 400);
        }
        return null;
    }
    public function validateEmail($email): ?JsonResponse
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Invalid email format'], 400);
        }
        return null;
    }
    public function validateMessage($message): ?JsonResponse
    {
        if (!preg_match("/^[a-zA-Z-' ]*$/",$message)) {
            return new JsonResponse(['error' => 'Only letters and white space allowed'], 400);
        }
        return null;
    }

}
