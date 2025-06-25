<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Question;
use App\Form\CommentTypeForm;
use App\Form\QuestionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'question_form')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        $question = new Question();
        $formQuestion = $this->createForm(QuestionFormType::class, $question);

        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $question->setNbrOfResponse(0);
            $question->setRating(0);
            $question->setCreatedAt(new \DateTimeImmutable());
            $em->persist($question);
            $em->flush();
            $this->addFlash('success', 'Votre question a été ajoutée');
            return $this->redirectToRoute('home');
        }

        return $this->render('question/index.html.twig', [
            'form' => $formQuestion->createView(),
        ]);
    }

    #[Route('/question/{id}', name: 'question_show')]
    public function show(Question $question, Request $request, EntityManagerInterface $em) : Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentTypeForm::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setQuestion($question);
            $comment->setRating(0);
            $question->setNbrOfResponse($question->getNbrOfResponse() + 1);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a été ajouté');
            return $this->redirect($request->getUri());
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'form' =>  $commentForm->createView(),
        ]);
    }

    #[Route('/question//rating/{id}/{score}', name: 'question_rating')]
    public function ratingQuestion(Question $question, int $score, EntityManagerInterface $em, Request $request): Response
    {
        $question->setRating($question->getRating() + $score);
        $em->flush();
        $referer =  $request->headers->get('referer');
        return $referer ? $this->redirect($referer) :  $this->redirectToRoute('home');
    }

    #[Route('/comment/rating/{id}/{score}', name: 'comment_rating')]
    public function ratingComment(Comment $comment, int $score, EntityManagerInterface $em, Request $request): Response
    {
        $comment->setRating($comment->getRating() + $score);
        $em->flush();
        $referer =  $request->headers->get('referer');
        return $referer ? $this->redirect($referer) :  $this->redirectToRoute('home');
    }
}
