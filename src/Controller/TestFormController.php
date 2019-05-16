<?php


namespace App\Controller;


use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuizTable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestFormController extends AbstractController
{
    /**
     * @Route("/admin/createQuestionsTest", name="create_quiz")
     */
    public function createQuiz() : Response{
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('test/createQuiz.html.twig', array('user' => $user));
    }

    /**
     * @Route("/admin/getCreatedQuiz", name="get_created_quiz")
     */
    public function add() : Response{
        $user = $this->get('security.token_storage')->getToken()->getUser();

/*        foreach ($_POST as $key => $value) {
            echo "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";
        }
        die();*/
        $entityManager = $this->getDoctrine()->getManager();
        $newQuiz = new QuizTable();
        $newQuiz->setTitle($_POST['title']);
        $newQuiz->setDescription($_POST['description']);
        $newQuiz->setIsActive(1);
        $newQuiz->setDateOfCreation(new \DateTime());

        (isset($_POST['canLook'])) ? $newQuiz->setCanLook($_POST['canLook']) : $newQuiz->setCanLook(false);

        $arrayOfQuestions = [];

        foreach ($_POST  as $key => $value){
            if (strpos($key,"question") !== false){
                $newQuestion = new Question();
                $newQuestion->setTitle($value);
                $arrayOfQuestions[] = $newQuestion;
            }

            if (strpos($key,"answer") !== false){
                $newAnswer = new Answer();
                $newAnswer->setTitle($value);
                $question = $arrayOfQuestions[sizeof($arrayOfQuestions)-1];
                $question->addAnswer($newAnswer);
            }

            if (strpos($key,"checkbox") !== false){
                $collectionOfAnswers = $arrayOfQuestions[sizeof($arrayOfQuestions)-1]->getAnswers();
                $lastAnswer = $collectionOfAnswers[sizeof($collectionOfAnswers)-1];
                $lastAnswer->setIsCorrect(1);
            }
        }

        foreach ($arrayOfQuestions as $question){
            $newQuiz->addQuestion($question);
        }


        $entityManager->persist($newQuiz);
        $entityManager->flush();

        return $this->render('test/createQuiz.html.twig', array('user' => $user, 'success' => 'Successfully created'));
    }


    /**
     * @Route("/admin/editQuiz/{quiz}", name="edit_quiz")
     */
    public function editQuiz(int $quiz) : Response{
        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($quiz);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($quiz === null){
            return $this->redirectToRoute('quiz_list',array('user' => $user));
        }
        return $this->render('test/editQuiz.html.twig', array('user' => $user, 'quiz' => $quiz));
    }

    /**
     * @Route("/admin/getInformationAboutQuiz/{quiz}", name="info")
     */
    public function getInformationAboutQuiz(int $quiz) : Response{
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($quiz);
        $arrayOfInformation = [];
        $arrayOfAnswers = [];

        foreach ($quiz->getQuestion() as $question){
            $arrayOfAnswers[] = sizeof($question->getAnswers());
        }

        $arrayOfInformation['countOfAnswers'] = $arrayOfAnswers;
        $response = new JsonResponse($arrayOfInformation);

        return $response;
    }

    /**
     * @Route("/admin/getEditQuiz/{quiz}", name="get_edit_quiz", requirements={"quiz"="\d+"})
     */
    public function edit(int $quiz) : Response{
        $entityManager = $this->getDoctrine()->getManager();
        $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($quiz);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($quiz === null){
            return $this->redirectToRoute('quiz_list',array('user' => $user));
        }
        $quiz->setTitle($_POST['title']);
        $quiz->setDescription($_POST['description']);
        $quiz->setIsActive(1);

        (isset($_POST['canLook'])) ? $quiz->setCanLook($_POST['canLook']) : $quiz->setCanLook(false);
        $quiz->setDateOfCreation(new \DateTime());

        $quiz->clearQuestion();

        $arrayOfQuestions = [];

        foreach ($_POST  as $key => $value){
            if (strpos($key,"question") !== false){
                $newQuestion = new Question();
                $newQuestion->setTitle($value);
                $arrayOfQuestions[] = $newQuestion;
            }

            if (strpos($key,"answer") !== false){
                $newAnswer = new Answer();
                $newAnswer->setTitle($value);
                $question = $arrayOfQuestions[sizeof($arrayOfQuestions)-1];
                $question->addAnswer($newAnswer);
            }

            if (strpos($key,"checkbox") !== false){
                $collectionOfAnswers = $arrayOfQuestions[sizeof($arrayOfQuestions)-1]->getAnswers();
                $lastAnswer = $collectionOfAnswers[sizeof($collectionOfAnswers)-1];
                $lastAnswer->setIsCorrect(true);
            }
        }

        foreach ($arrayOfQuestions as $question){
            $quiz->addQuestion($question);
        }


        $entityManager->persist($quiz);
        $entityManager->flush();

        return $this->render('test/editQuiz.html.twig', array('quiz' => $quiz, 'user' => $user, 'success' => 'Successfully update'));
    }
}