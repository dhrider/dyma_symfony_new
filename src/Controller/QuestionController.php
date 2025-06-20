<?php

namespace App\Controller;

use App\Form\QuestionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'question_form')]
    public function index(Request $request): Response
    {
        $formQuestion = $this->createForm(QuestionFormType::class);

        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {

        }

        return $this->render('question/index.html.twig', [
            'form' => $formQuestion->createView(),
        ]);
    }

    #[Route('/question/{id}', name: 'question_show')]
    public function show(Request $request, string $id) : Response
    {
        $question = [
            'title' => 'je suis une question',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nisl ipsum, tincidunt sit amet neque in, interdum venenatis mi. Nulla non vestibulum odio, sed vestibulum dolor. Nunc faucibus mollis nibh. Curabitur vel sapien urna. Sed a ex eget quam congue interdum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus efficitur sem neque, vel porta urna tincidunt tempor. Phasellus rhoncus commodo gravida. Etiam erat arcu, lobortis et feugiat ut, tristique non ex. In venenatis libero non euismod blandit. Nulla quis eros volutpat, scelerisque quam id, tincidunt mi. Aenean sollicitudin ipsum nec cursus consequat. Fusce id mi sit amet felis dignissim dignissim varius id nibh.',
            'rating' => 20,
            'author'  => [
                'name' => 'Susan Doe',
                'avatar' => 'https://randomuser.me/api/portraits/women/67.jpg'
            ],
            'nbrOfResponse' => 15
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question
        ]);
    }
}
