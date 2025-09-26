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
        error_log("BrevoService constructor:");
        error_log("- API Key length: " . strlen($brevoApiKey));
        error_log("- My Email: " . $myEmail);
        error_log("- API Key starts with: " . substr($brevoApiKey, 0, 10));

        if (empty($brevoApiKey)) {
            throw new \Exception('BREVO_API_KEY is empty or not set');
        }

        if (empty($myEmail)) {
            throw new \Exception('MY_EMAIL is empty or not set');
        }
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
                'sender' => ['name' => 'Portfolio Contact', $this->myEmail],
                'replyTo' => ['email' => $email],
                'to' => [['email' => $this->myEmail]],
                'htmlContent' => "
                    <h3>New message from portfolio</h3>
                    <p><strong>From:</strong> {$email}</p>
                    <p><strong>Message:</strong></p>
                    <p>" . nl2br(htmlspecialchars($message)) . "</p>
                "
            ]);

            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);

            // Logga resultatet
            error_log('Brevo API Response: ' . json_encode($result));
            error_log('Email sent successfully to: ' . $this->myEmail);
        } catch (\Exception $e) {
            error_log('Brevo API Error: ' . $e->getMessage());
            throw $e;
        }

        }

}
