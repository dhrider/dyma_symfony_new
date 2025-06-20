<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $questions = [
            [
                'id' => '1',
                'title' => 'je suis une question',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nisl ipsum, tincidunt sit amet neque in, interdum venenatis mi. Nulla non vestibulum odio, sed vestibulum dolor. Nunc faucibus mollis nibh. Curabitur vel sapien urna. Sed a ex eget quam congue interdum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus efficitur sem neque, vel porta urna tincidunt tempor. Phasellus rhoncus commodo gravida. Etiam erat arcu, lobortis et feugiat ut, tristique non ex. In venenatis libero non euismod blandit. Nulla quis eros volutpat, scelerisque quam id, tincidunt mi. Aenean sollicitudin ipsum nec cursus consequat. Fusce id mi sit amet felis dignissim dignissim varius id nibh.',
                'rating' => 20,
                'author'  => [
                    'name' => 'Susan Doe',
                    'avatar' => 'https://randomuser.me/api/portraits/women/67.jpg'
                ],
                'nbrOfResponse' => 15
            ],
            [
                'id' => '2',
                'title' => 'je suis une question',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nisl ipsum, tincidunt sit amet neque in, interdum venenatis mi. Nulla non vestibulum odio, sed vestibulum dolor. Nunc faucibus mollis nibh. Curabitur vel sapien urna. Sed a ex eget quam congue interdum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus efficitur sem neque, vel porta urna tincidunt tempor. Phasellus rhoncus commodo gravida. Etiam erat arcu, lobortis et feugiat ut, tristique non ex. In venenatis libero non euismod blandit. Nulla quis eros volutpat, scelerisque quam id, tincidunt mi. Aenean sollicitudin ipsum nec cursus consequat. Fusce id mi sit amet felis dignissim dignissim varius id nibh.',
                'rating' => 0,
                'author'  => [
                    'name' => 'Philippe Bordmann',
                    'avatar' => 'https://randomuser.me/api/portraits/men/23.jpg'
                ],
                'nbrOfResponse' => 10
            ],
            [
                'id' => '3',
                'title' => 'je suis une question',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nisl ipsum, tincidunt sit amet neque in, interdum venenatis mi. Nulla non vestibulum odio, sed vestibulum dolor. Nunc faucibus mollis nibh. Curabitur vel sapien urna. Sed a ex eget quam congue interdum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus efficitur sem neque, vel porta urna tincidunt tempor. Phasellus rhoncus commodo gravida. Etiam erat arcu, lobortis et feugiat ut, tristique non ex. In venenatis libero non euismod blandit. Nulla quis eros volutpat, scelerisque quam id, tincidunt mi. Aenean sollicitudin ipsum nec cursus consequat. Fusce id mi sit amet felis dignissim dignissim varius id nibh.',
                'rating' => -15,
                'author'  => [
                    'name' => 'Jane Doe',
                    'avatar' => 'https://randomuser.me/api/portraits/women/17.jpg'
                ],
                'nbrOfResponse' => 10
            ]
        ];

        return $this->render('home/index.html.twig', [
            'questions' => $questions
        ]);
    }
}
