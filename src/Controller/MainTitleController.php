<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
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

            $userPhoto = null;
            if ($user !== 'anon.'){
                $userPhoto = $user->getUserPhoto();
            }

            if ($userPhoto === null) {
                $userPhoto = 'https://static.thenounproject.com/png/17241-200.png';
            } else {
                $userPhoto = 'https://drive.google.com/uc?id=' . $userPhoto->getSource();
            }


        try {
            if ($user->getIsActive() == 0) {
                return $this->render('user/ban.html.twig', array('userPhoto' => $userPhoto));
            }
        } catch (\Error $e) {
            $quizzes = $this->getDoctrine()->getRepository(QuizTable::class)->findAll();
            $result = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findAll();
            return $this->render('maintitle/maintitle.html.twig', array('quizzes' => $quizzes, 'user' => $user, 'result' => $result, 'userPhoto' => $userPhoto));

        }
        $quizzes = $this->getDoctrine()->getRepository(QuizTable::class)->findAll();
        $result = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findAll();

        $userPhoto = $user->getUserPhoto();
        if ($userPhoto === null){
            $userPhoto = 'https://static.thenounproject.com/png/17241-200.png';
        } else {
            $userPhoto = 'https://drive.google.com/uc?id=' . $userPhoto->getSource();
        }

        return $this->render('maintitle/maintitle.html.twig', array('quizzes' => $quizzes,
            'user' => $user, 'result' => $result, 'userPhoto' => $userPhoto));
    }
}
