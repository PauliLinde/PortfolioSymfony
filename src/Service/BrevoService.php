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

    public function __construct(string $brevoApiKey, string $myEmail)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $brevoApiKey);

        $httpClient = new Client([
            'verify' => false,
        ]);

        $this->apiInstance = new TransactionalEmailsApi($httpClient, $config);
        $this->myEmail = $myEmail;
    }

    public function sendEmail($email, $message): void
    {
        try{
            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => 'New message from my portfolio',
                'sender' => ['name' => 'Portfolio Contact', 'email' => $this->myEmail],
                'to' => [['email' => $this->myEmail]],
                'htmlContent' => "
                    <h3>New message from portfolio</h3>
                    <p><strong>From:</strong> {$email}</p>
                    <p><strong>Message:</strong></p>
                    <p>" . nl2br(htmlspecialchars($message)) . "</p>
                "
            ]);

            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            error_log('Brevo API Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
