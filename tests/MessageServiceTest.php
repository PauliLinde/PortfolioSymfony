<?php

namespace App\Tests;

use App\Service\BrevoService;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class MessageServiceTest extends TestCase
{
    public function testAddToDatabase(){
        $brevoMock = $this->createMock(BrevoService::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $messageService = new MessageService($brevoMock, $entityManagerMock);
        $email = 'test@test.com';
        $message = 'test';
        $response = $messageService->addToDatabase($email, $message);
        self::assertTrue($response['success']);
    }
}
