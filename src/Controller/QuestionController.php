<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Question;
use App\Entity\Vote;
use App\Form\CommentTypeForm;
use App\Form\QuestionFormType;
use App\Repository\QuestionRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'question_form')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $question = new Question();
        $formQuestion = $this->createForm(QuestionFormType::class, $question);

        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $question->setNbrOfResponse(0);
            $question->setRating(0);
            $question->setCreatedAt(new \DateTimeImmutable());
            $question->setAuthor($user);
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
    public function show(QuestionRepository $questionRepository, Request $request, EntityManagerInterface $em, int $id) : Response
    {
        $question = $questionRepository->getQuestionWithCommentsAndAuthors($id);

        $options = ['question' => $question];
        $user = $this->getUser();
        if ($user) {
            $comment = new Comment();
            $commentForm = $this->createForm(CommentTypeForm::class, $comment);
            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $comment->setCreatedAt(new \DateTimeImmutable());
                $comment->setQuestion($question);
                $comment->setRating(0);
                $comment->setAuthor($user);
                $question->setNbrOfResponse($question->getNbrOfResponse() + 1);
                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', 'Votre commentaire a été ajouté');
                return $this->redirect($request->getUri());
            }
            $options['form'] = $commentForm->createView();
        }

        return $this->render('question/show.html.twig', $options);
    }

    #[Route('/question//rating/{id}/{score}', name: 'question_rating')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function ratingQuestion(Question $question, int $score, EntityManagerInterface $em, Request $request, VoteRepository $voteRepository): Response
    {
        $user = $this->getUser();
        if ($user !== $question->getAuthor()) {
            $vote = $voteRepository->findOneBy(['author' => $user, 'question' => $question]);
            if ($vote) {
                if (($vote->getIsLiked() && $score > 0) || (!$vote->getIsLiked() && $score < 0)) {
                    $em->remove($vote);
                    $question->setRating($question->getRating() + ($score > 0 ? -1 : 1));
                } else {
                    $vote->setIsLiked(!$vote->getIsLiked());
                    $question->setRating($question->getRating() + ($score > 0 ? 2 : -2));
                }
            } else {
                $vote = new Vote();
                $vote->setAuthor($user);
                $vote->setQuestion($question);
                $vote->setIsLiked($score > 0);
                $question->setRating($question->getRating() + $score);
                $em->persist($vote);
            }
            $em->flush();
        }


        $referer =  $request->headers->get('referer');
        return $referer ? $this->redirect($referer) :  $this->redirectToRoute('home');
    }

    #[Route('/comment/rating/{id}/{score}', name: 'comment_rating')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function ratingComment(Comment $comment, int $score, EntityManagerInterface $em, Request $request, VoteRepository $voteRepository): Response
    {
        $user = $this->getUser();
        if ($user !== $comment->getAuthor()) {
            $vote = $voteRepository->findOneBy((['author' => $user, 'comment' => $comment]));
            if ($vote) {
                if (($vote->getIsLiked() && $score > 0) || (!$vote->getIsLiked() && $score < 0)) {
                    $em->remove($vote);
                    $comment->setRating($comment->getRating() + ($score > 0 ? -1 : 1));
                } else {
                    $vote->setIsLiked(!$vote->getIsLiked());
                    $comment->setRating($comment->getRating() + ($score > 0 ? 2 : -2));
                }
            } else {
                $vote = new Vote();
                $vote->setAuthor($user);
                $vote->setComment($comment);
                $vote->setIsLiked($score > 0);
                $comment->setRating($comment->getRating() + $score);
                $em->persist($vote);
            }
            $em->flush();
        }
        $referer =  $request->headers->get('referer');
        return $referer ? $this->redirect($referer) :  $this->redirectToRoute('home');
    }
}
