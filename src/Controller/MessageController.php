<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\Type\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{

    #[Route('/newMessage', methods: ['POST'])]
    public function newMessage(Request $request, EntityManagerInterface $entityManager): void
    {

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $action = $form->getData();
            $entityManager->persist($action);
            $entityManager->flush();
        }
    }
}
