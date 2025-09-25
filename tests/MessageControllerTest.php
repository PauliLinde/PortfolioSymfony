<?php

namespace App\Tests;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp():void {
        parent::setUp();
        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->createSchema();
    }

    private function createSchema(): void
    {
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->createSchema($metadata);
    }

    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }

    public function testAddNewMessage(): void{
        $this->client->request(
            'POST',
            '/newMessage',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'laura@gmail.com',
                'message' => 'Hello from Laura',
            ])
        );

        $messages = $this->entityManager->getRepository(Message::class)
            ->findBy(['email' => 'laura@gmail.com']);
        $this->assertCount(1, $messages);
        $this->assertSame('Hello from Laura', $messages[0]->getMessage());
    }

    protected function tearDown(): void
    {
        if ($this->entityManager) {
            $messages = $this->entityManager->getRepository(Message::class)->findAll();

            foreach ($messages as $message) {
                $this->entityManager->remove($message);
            }

            $this->entityManager->flush();
            $this->entityManager->close();
            $this->entityManager = null;
        }
        parent::tearDown();
    }
}
