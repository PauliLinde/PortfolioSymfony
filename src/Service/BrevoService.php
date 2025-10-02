<?php
namespace App\Service;

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class BrevoService
{

    private $apiInstance;
    private $myEmail;

    public function __construct(string $brevoApiKey, string $myEmail, ?TransactionalEmailsApi $apiInstance = null)
    {
        $this->apiInstance = $apiInstance  ?? $this->createApiInstance($brevoApiKey);
        $this->myEmail = $myEmail;
    }

    private function createApiInstance(string $brevoApiKey): TransactionalEmailsApi
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $brevoApiKey);
        $httpClient = new Client(['verify' => false]);
        return new TransactionalEmailsApi($httpClient, $config);
    }

    public function sendEmail($name, $email, $message): void
    {
        try{
            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => 'New message from my portfolio',
                'sender' => ['name' => 'Portfolio Contact', 'email' => $this->myEmail],
                'to' => [['email' => $this->myEmail]],
                'htmlContent' => "
                    <h3>New message from {$name}</h3>
                    <p><strong>From:</strong> {$email}</p>
                    <p><strong>Message:</strong></p>
                    <p>" . nl2br(htmlspecialchars($message)) . "</p>
                "
            ]);

            $this->apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            error_log('Brevo API Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
