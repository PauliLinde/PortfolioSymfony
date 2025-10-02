<?php

namespace App\Tests;

use App\Validator\MessageValidator;
use PHPUnit\Framework\TestCase;

class MessageValidatorTest extends TestCase
{
    private $validator;
    private $rightName = 'Anna';
    private $wrongName = '£@';
    private $rightEmail = 'test@test.com';
    private $wrongEmail = 'test.com';

    public function setUp(): void
    {
        $this->validator = new MessageValidator();
    }

    public function testValidateNameWithValidName(){
        $this->validator->validateName($this->rightName);
        $this->assertTrue(true);
    }

    public function testValidateNameWithInvalidName(){
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only letters and white space allowed');

        $this->validator->validateName($this->wrongName);
    }

    public function testValidateEmailWithValidEmail(){
        $this->validator->validateEmail($this->rightEmail);
        $this->assertTrue(true);
    }

    public function testValidateEmailWithInvalidEmail(){
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address');

        $this->validator->validateEmail($this->wrongEmail);
    }
}
