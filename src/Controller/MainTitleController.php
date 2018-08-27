<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\QuizTable;
use App\Entity\ResultOfQuiz;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainTitleController extends AbstractController
{
    /**
     * @Route("/", name="quiz_list")
     *
     */
    public function index() : Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        try {
            if ($user->getIsActive() == 0) {
                return $this->render('user/ban.html.twig');
            }
        } catch (\Error $e) {
            $quizzes = $this->getDoctrine()->getRepository(QuizTable::class)->findAll();
            $result = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findAll();
            return $this->render('maintitle/maintitle.html.twig', array('quizzes' => $quizzes, 'user' => $user, 'result' => $result));

        }
        $quizzes = $this->getDoctrine()->getRepository(QuizTable::class)->findAll();
        $result = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findAll();
        return $this->render('maintitle/maintitle.html.twig', array('quizzes' => $quizzes, 'user' => $user, 'result' => $result));
    }
}
