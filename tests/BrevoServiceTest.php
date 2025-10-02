<?php

namespace App\Tests;

use App\Service\BrevoService;
use Brevo\Client\Api\TransactionalEmailsApi;
use PHPUnit\Framework\TestCase;

class BrevoServiceTest extends TestCase
{
    public function testSendEmail(){
        $apiMoch = $this->createMock(TransactionalEmailsApi::class);
        $brevoService = new BrevoService('test', 'test@mail.com', $apiMoch);

        $brevoService->sendEmail('test@mail.com', 'test message');

        $this->assertTrue(true);
    }

}
