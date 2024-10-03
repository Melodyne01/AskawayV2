<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OpenAIController extends AbstractController
{
    #[Route('/openai/chatGPT', name: 'app_openai_chatGPT')]
    public function index(string $message): Response
    {
        $openAI_endpoint = "";
        $openAI_token = "";

        $data = array(
            "model" => "gpt-4o-mini",
            "message" => array(
                array(
                    "role" => "user",
                    "content" => "What is a LLM?"
                ),
                array(
                    "role" => "system",
                    "content" => "You are a helpful assistant."
                )
            ),
            "max_tokens" => 100,
            "temperature" => 0.7
        );

        $headers = array(
            
        );

        return $this->render('open_ai/index.html.twig', [
        ]);
    }
}
