<?php

namespace App\Controller;

use App\Entity\QuizTable;
use App\Entity\ResultOfQuiz;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserResultsController extends AbstractController
{
    /**
     * @Route("/results", name="all_results")
     */
    public function index()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userPhoto = $user->getUserPhoto();

        if ($userPhoto === null){
            $userPhoto = 'https://static.thenounproject.com/png/17241-200.png';
        } else {
            $userPhoto = 'https://drive.google.com/uc?id=' . $userPhoto->getSource();
        }

        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig', array('userPhoto' => $userPhoto));
        }

        $resultsOfUser = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findBy(array('user' => $user));
        $quizes = $this->getDoctrine()->getRepository(QuizTable::class)->findAll();
        $arrayOfResultsAll = array();
        foreach ($quizes as $q) {
            if($this->getDoctrine()->getRepository(User::class)->getAllResult($q) != null){
                array_push($arrayOfResultsAll, $this->getDoctrine()->getRepository(User::class)->getAllResult($q));
            }
        }
        return $this->render('user/user_results.html.twig', array('user' => $user, 'resultsOfUser' => $resultsOfUser, 'arrayOfResultsAll' => $arrayOfResultsAll, 'userPhoto' => $userPhoto));
    }
}