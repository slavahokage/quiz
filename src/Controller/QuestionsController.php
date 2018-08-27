<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\QuizTable;
use App\Entity\ResultOfQuiz;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends AbstractController
{


    /**
     * @Route("/questions/{page}", name="questions_list", requirements={"page"="\d+"})
     *
     */
    public function index(int $page): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig');
        }
        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($page);

        if($quiz === null){
            return $this->redirectToRoute('quiz_list');
        }

        $result = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findOneBy(['user' => $user, 'quiz' => $quiz]);
        if ($result != null) {
            if ($quiz->getIsActive() == 0) {
                return $this->redirectToRoute('quiz_list');
            }
        }

        if ($result != null) {
            if ($result->getIsOver() == 1) {
                return $this->redirectToRoute('results', array('page' => $page));
            }
        }

        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($page);
        return $this->render('questions/questions.html.twig', array('quiz' => $quiz));
    }

    /**
     * @Route("questions/{page}/showResults", name="results", requirements={"page"="\d+"})
     *
     */
    public function showResults(int $page) : Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig');
        }

        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($page);

        if($quiz === null){
            return $this->redirectToRoute('quiz_list');
        }

        if ($quiz->getIsActive() == 0) {
            return $this->redirectToRoute('quiz_list');
        }

        $resultOfUser= $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findOneBy(
            array('user' => $user, 'quiz' => $quiz));
        if ($resultOfUser == null){
            return $this->redirectToRoute('quiz_list');
        }else if ($resultOfUser->getIsOver() == 0){
            return $this->redirectToRoute('quiz_list');
        }
        $topTree = $this->getDoctrine()->getRepository(User::class)->getTopTreeResultsOfUsers($quiz);
        $results = $this->getDoctrine()->getRepository(User::class)->getAllResult($quiz);
        return $this->render('questions/results.html.twig',array('user' => $user, 'quiz' => $quiz, 'topTree' =>$topTree, 'results' =>$results));
    }


    /**
     * @Route("/questions/ajax", name="ajax_request")
     *
     */
    public function fixationOfResults(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $cookies = $request->cookies;
        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($cookies->get('quiz'));
            $result = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findOneBy(
                array('user' => $user, 'quiz' => $quiz));

        if ($result === null) {
            $result = new ResultOfQuiz();
            $result->setUser($this->getUser());
            $result->setScore(0);
            $result->setQuiz($quiz);
            $result->setCurrentQuestion(0);
            $result->setIsOver(false);
            $result->setTime(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($result);
            $entityManager->flush();
        }


        if($request->request->has('isOver')) {
            $result->setIsOver(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($result);
            $entityManager->flush();
        }

        if ($request->request->has('time')){
            $result->setTime($request->request->get('time'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($result);
            $entityManager->flush();
        }

        if ($request->request->has('currentQuestion')) {
            $result->setCurrentQuestion((int)$request->request->get('currentQuestion'));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($result);
                $entityManager->flush();
        }

        if ($request->request->has('correct')) {
            $result->setScore($result->getScore() + 1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($result);
            $entityManager->flush();
            $response = new Response();
            $response->headers->clearCookie('correct', 'questions/ajax');
            $response->send();
            return $response;
        }

        return new Response();
    }

    /**
     * @Route("/questions/currentQuestion", name="current_question")
     *
     */
    public function currentQuestion(Request $request): Response
    {
        $cookies = $request->cookies;
        if ($request->request->has('giveMeCurrentQuestion')){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($cookies->get('quiz'));

            $resultOfUser = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findOneBy(
                array('user' => $user, 'quiz' => $quiz));
            if ($resultOfUser === null) {
                $resultOfUser= new ResultOfQuiz();
                $resultOfUser->setUser($this->getUser());
                $resultOfUser->setScore(0);
                $resultOfUser->setQuiz($quiz);
                $resultOfUser->setCurrentQuestion(0);
                $resultOfUser->setIsOver(false);
                $resultOfUser->setTime(0);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($resultOfUser);
                $entityManager->flush();
            }
            $currentQuestion = $resultOfUser->getCurrentQuestion();
            $arrData = ['output' => $currentQuestion];
            return new JsonResponse($arrData);
        }
        return new JsonResponse();
    }

    /**
     * @Route("/questions/getScore", name="score")
     *
     */
    public function getScore(Request $request): Response
    {
        $cookies = $request->cookies;
        if ($request->request->has('giveMeScore')){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($cookies->get('quiz'));
            $resultOfUser = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findOneBy(
                array('user' => $user, 'quiz' => $quiz));
            if ($resultOfUser === null) {
                $resultOfUser= new ResultOfQuiz();
                $resultOfUser->setUser($this->getUser());
                $resultOfUser->setScore(0);
                $resultOfUser->setQuiz($quiz);
                $resultOfUser->setCurrentQuestion(0);
                $resultOfUser->setIsOver(false);
                $resultOfUser->setTime(0);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($resultOfUser);
                $entityManager->flush();
            }
            $score= $resultOfUser->getScore();
            $arrData = ['output' => $score];
            return new JsonResponse($arrData);
        }
        return new JsonResponse();
    }


    /**
     * @Route("/questions/getTime", name="time")
     *
     */
    public function getTime(Request $request) : Response
    {
        $cookies = $request->cookies;
        if ($request->request->has('giveMeTime')){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($cookies->get('quiz'));
            $resultOfUser = $this->getDoctrine()->getRepository(ResultOfQuiz::class)->findOneBy(
                array('user' => $user, 'quiz' => $quiz));
            if ($resultOfUser === null) {
                $resultOfUser= new ResultOfQuiz();
                $resultOfUser->setUser($this->getUser());
                $resultOfUser->setScore(0);
                $resultOfUser->setQuiz($quiz);
                $resultOfUser->setCurrentQuestion(0);
                $resultOfUser->setIsOver(false);
                $resultOfUser->setTime(0);
            }
            $time = $resultOfUser->getTime();
            $arrData = ['output' => $time];
            return new JsonResponse($arrData);
        }
        return new JsonResponse();
    }
}