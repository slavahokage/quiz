<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\AdminQuizTable;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuizTable;
use App\Entity\User;
use App\Form\QuestionType2;
use App\Form\QuestionTypeForEdit2;
use App\Form\QuizTableType;
use App\Form\QuizTableTypeForEdit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_page")
     *
     */
    public function admin() : Response
    {
        return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/admin/edit", name="admin_edit")
     *
     */
    public function adminEdit() : Response
    {
        $quizzes = $this->getDoctrine()->getRepository(QuizTable::class)->findAll();
        return $this->render('admin/admin_edit.html.twig',array('quizzes' => $quizzes));
    }

    /**
     * @Route("/admin/disableQuiz/{quiz}", name="disable_quiz", requirements={"quiz"="\d+"})
     *
     */
    public function disableQuiz(int $quiz) : Response
    {
        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($quiz);
        if($quiz === null){
            return $this->redirectToRoute('quiz_list');
        }

        $quiz->setIsActive(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $quizzes = $this->getDoctrine()->getRepository(QuizTable::class)->findAll();
        return $this->render('admin/admin_edit.html.twig',array('quizzes' => $quizzes));
    }

    /**
     * @Route("/admin/undisableQuiz/{quiz}", name="undisable_quiz", requirements={"quiz"="\d+"})
     *
     */
    public function undisableQuiz(int $quiz) : Response
    {
        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($quiz);
        if($quiz === null){
            return $this->redirectToRoute('quiz_list');
        }

        $quiz->setIsActive(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $quizzes = $this->getDoctrine()->getRepository(QuizTable::class)->findAll();
        return $this->render('admin/admin_edit.html.twig',array('quizzes' => $quizzes));
    }

    /**
     * @Route("/admin/userList", name="user_list")
     *
     */
    public function userList() : Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/userlist.html.twig',array('user' => $user));
    }

    /**
     * @Route("/admin/ajaxUserList", name="ajax_userlist")
     *
     */
    public function ajaxActivateOfUSer(Request $request): Response
    {

        if ($request->request->has('id') && $request->request->has('status')) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($request->request->get('id'));
            $user->setIsActive($request->request->get('status'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return new Response();

    }

    /**
     * @Route("/admin/edit/{quiz}", name="admin_edit_questions", requirements={"quiz"="\d+"})
     *
     */
    public function adminEditQuestions(Request $request, int $quiz) : Response
    {
        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($quiz);
        if($quiz === null){
            return $this->redirectToRoute('quiz_list');
        }

        $form = $this->createForm(QuizTableTypeForEdit::class, $quiz);
        $questions = $quiz->getQuestion();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($questions[0] == null) {
                return $this->render(
                    'admin/admin_questions.html.twig',
                    array('form' => $form->createView(), 'error' => 'Something went wrong! Input at least one answer!')
                );
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('edit_answers', array('quiz' => $quiz->getId(), 'number' => 0 ));
        }

        return $this->render('admin/admin_questions.html.twig', array(
            'form' => $form->createView(),));
    }

    /**
     * @Route("/admin/{quiz}/editAnswers/{number}", name="edit_answers", requirements={"quiz"="\d+","number"="\d+"})
     *
     */
    public function adminEditAnswers(Request $request, int $quiz, int $number) : Response
    {
        $quizForAnswers = $this->getDoctrine()->getRepository(QuizTable::class)->find($quiz);
        if($quizForAnswers === null){
            return $this->redirectToRoute('quiz_list');
        }

        $questions = $quizForAnswers->getQuestion();

        $currentQuestion = $questions[$number];
        $form = $this->createForm(QuestionTypeForEdit2::class, $currentQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($currentQuestion->getAnswers()[0] == null) {
                return $this->render(
                    'admin/admin_answers.html.twig',
                    array('form' => $form->createView(), 'error' => 'Something went wrong! Input at least one answer!')
                );
            }

            $countOfCorrectAnswers = 0;
            foreach ($currentQuestion->getAnswers() as $answer) {
                if ($answer->getIsCorrect() == 1) {
                    $countOfCorrectAnswers++;
                }
                if ($countOfCorrectAnswers > 1) {
                    break;
                }
            }
            if ($countOfCorrectAnswers != 1) {
                return $this->render(
                    'admin/admin_answers.html.twig',
                    array('form' => $form->createView(), 'error' => 'Something went wrong! More then one correct answer or no at all!')
                );
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $number++;
            if($questions[$number] == null){
                return $this->render('admin/admin_successfully_update.html.twig');
            }
            return $this->redirectToRoute('edit_answers', array('quiz' => $quiz, 'number' =>$number));
        }

        return $this->render('admin/admin_answers.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/{quiz}/createAnswers", name="create_answers", requirements={"quiz"="\d+"})
     *
     */
    public function creteAnswers(Request $request, int $quiz) : Response
    {
        $quizForAnswers = $this->getDoctrine()->getRepository(AdminQuizTable::class)->find($quiz);
        if($quizForAnswers === null){
            return $this->redirectToRoute('quiz_list');
        }

        $questions = $quizForAnswers->getQuestion();

        $currentQuestion = $questions[$quizForAnswers->getCurrentQuestion()];
        $form = $this->createForm(QuestionType2::class, $currentQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($currentQuestion->getAnswers()[0] == null) {
                return $this->render(
                    'admin/admin_answers.html.twig',
                    array('form' => $form->createView(), 'error' => 'Something went wrong! Input at least one answer!')
                );
            }

            $countOfCorrectAnswers = 0;
            foreach ($currentQuestion->getAnswers() as $answer) {
                if ($answer->getIsCorrect() == 1) {
                    $countOfCorrectAnswers++;
                }
                if ($countOfCorrectAnswers > 1) {
                    break;
                }
            }
            if ($countOfCorrectAnswers != 1) {
                return $this->render(
                    'admin/admin_answers.html.twig',
                    array('form' => $form->createView(), 'error' => 'Something went wrong! More then one correct answer or no at all!')
                );
            }


            $entityManager = $this->getDoctrine()->getManager();
            $quizForAnswers->setCurrentQuestion($quizForAnswers->getCurrentQuestion() + 1);
            $entityManager->flush();

            $currentQuestion = $questions[$quizForAnswers->getCurrentQuestion()];
            $form = $this->createForm(QuestionType2::class, $currentQuestion);

        }

        if ($questions [$quizForAnswers->getCurrentQuestion()] == null) {
            $userQuiz = new QuizTable();
            $userQuiz->setIsActive(1);
            $userQuiz->setDateOfCreation( new \DateTime());
            $userQuiz->setTitle($quizForAnswers->getTitle());
            $userQuiz->setDescription($quizForAnswers->getDescription());

            foreach ($questions as $q) {
                $userQuestions = new Question();
                foreach ($q->getAnswers() as $a){
                    $userAnswer = new Answer();
                    $userAnswer->setTitle($a->getTitle());
                    $userAnswer->setIsCorrect($a->getIsCorrect());
                    $userQuestions->addAnswer($userAnswer);
                }
                $userQuestions->setTitle($q->getTitle());
                $userQuiz->addQuestion($userQuestions);
            }

            $entityManager = $this->getDoctrine()->getManager();
            foreach ($questions as $q) {
                foreach ($q->getAnswers() as $a) {
                    $q->removeAnswer($a);
                    $entityManager->remove($q);
                }
                $quizForAnswers->removeQuestion($q);
            }

            $entityManager->remove($quizForAnswers);
            $entityManager->persist($userQuiz);
            $entityManager->flush();
            return $this->render('admin/admin_successfully_create.twig');
        }


        return $this->render('admin/admin_answers.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/createQuestions", name="create_questions")
     *
     */
    public function createQuestions(Request $request) : Response
    {
        $quiz = new AdminQuizTable();
        $form = $this->createForm(QuizTableType::class, $quiz);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                if ($quiz->getQuestion()[0] == null) {
                    return $this->render(
                        'admin/admin_questions.html.twig',
                        array('form' => $form->createView(), 'error' => 'Something went wrong! Input at least one question!')
                    );
                }
                $quiz->setCurrentQuestion(0);
                $entityManager->persist($quiz);
                $entityManager->flush();
                return $this->redirectToRoute('create_answers', array('quiz' => $quiz->getId()));
            } catch (\Exception $e) {
                return $this->render(
                    'admin/admin_questions.html.twig',
                    array('form' => $form->createView(), 'error' => 'Something went wrong! This quiz is already exists!')
                );
            }
        }

        return $this->render('admin/admin_questions.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/admin/autocomplete", name="autocomplete")
     *
     */
    public function autocomplete(Request $request) : Response
    {

        $arrayOfQuestions = array();
        if ($request->request->has('questions')) {
            $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();

            foreach ($questions as $q) {
                array_push($arrayOfQuestions,$q->getTitle());
            }
        }

        return new JsonResponse($arrayOfQuestions);
    }
}