<?php

namespace App\Tests;

use App\Service\BrevoService;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{

    public function testAddNewMessage(): void{
        $client = static::createClient();
        $container = static::getContainer();

        $brevoMock = $this->createMock(BrevoService::class);
        $container->set(BrevoService::class, $brevoMock);

        $messageServiceMock = $this->createMock(MessageService::class);
        $messageServiceMock->method('addToDatabase')
            ->willReturn(['success' => true, 'id' => 123]);
        $container->set(MessageService::class, $messageServiceMock);

        $client->request(
            'POST',
            '/newMessage',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@example.com',
                'message' => 'Test message',
            ])
        );

        $this->assertResponseIsSuccessful();
    }
}
