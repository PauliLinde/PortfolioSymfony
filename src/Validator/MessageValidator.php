<?php

namespace App\Validator;

class MessageValidator
{
    public function validateData(?array $data): void
    {
        if (!$data) {
            throw new \InvalidArgumentException('Invalid JSON data');
        }

        if (!isset($data['email'])) {
            throw new \InvalidArgumentException('Email field is missing');
        }

        if (!isset($data['message'])) {
            throw new \InvalidArgumentException('Message field is missing');
        }

        $this->validateEmail($data['email']);
        $this->validateMessage($data['message']);
    }
    public function validateName($name): void
    {
        if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            throw new \InvalidArgumentException('Only letters and white space allowed');
        }
    }
    public function validateEmail($email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }
    }
    public function validateMessage($message): void
    {
        if (!preg_match("/^[a-zA-Z-' ]*$/",$message)) {
            throw new \InvalidArgumentException('Only letters and white space allowed');
        }
    }

}
