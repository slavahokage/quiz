<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\QuizTable;
use App\Form\CommentType;
use App\Traits\GoogleClient;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CommentController extends AbstractController
{
    use GoogleClient;

    /**
     * @Route("/createComment", name="create_comment")
     *
     */
    public function createComment(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $comment = new Comment();

        if (isset($_POST['quiz'])) {
            try{
                $quiz = $this->getDoctrine()->getRepository(QuizTable::class)->find($_POST['quiz']);
                $comment->setQuiz($quiz);
            }catch (\Exception $exception){
                return $this->redirectToRoute('quiz_list');
            }
        } else if (isset($_POST['article'])) {
            try{
                $article = $this->getDoctrine()->getRepository(Article::class)->find($_POST['article']);
                $comment->setArticle($article);
            }catch (\Exception $exception){
                return $this->redirectToRoute('article_list');
            }
        }

        $comment->setTitle($_POST['title']);

        $uploadFile = null;
        $realName = null;

        if (isset($_FILES['file'])) {
            if ($_FILES['file']['error'] == 0) {
                $uploadFile = $this->uploadFile($_FILES['file']);
                $realName = $_FILES['file']['name'];
                $comment->setTitle($_POST['title']);
                $comment->setMime($_FILES['file']['type']);
                $comment->setRealName($_FILES['file']['name']);
                $pathParts = pathinfo($_FILES["file"]["name"]);
                $comment->setExtension($pathParts['extension']);
                $comment->setSource($uploadFile);
            }
        }


        $time = new DateTime();
        $comment->setDateOfCreation($time);
        $comment->setCreator($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();


        return new JsonResponse(['response' => 'ok', 'hash' => $uploadFile, 'realName' => $realName,'time' => $comment->getDateOfCreation(),
            'id' => $comment->getId(), 'commentLength' => $this->getDoctrine()->getRepository(Comment::class)->count([])]);
    }

    /**
     * @Route("/showComments", name="show_comments")
     *
     */
    public function showComments(SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $expression = [];
        if (isset($_POST['quiz'])) {
            $expression['quiz'] = $_POST['quiz'];
        } else if (isset($_POST['article'])) {
            $expression['article'] = $_POST['article'];
        }
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy($expression, array('dateOfCreation' => 'ASC'));

        foreach ($comments as $comment){
            $serializer->normalize($comment->getDateOfCreation());
        }

        $json = $serializer->serialize($comments, 'json', array('groups' => array('api')));

        return new Response($json);
    }

    /**
     * @Route("/deleteComment", name="delete_comment")
     *
     */
    public function deleteComment()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($_POST['comment']);
        if ($comment->getCreator() === $user) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            return new JsonResponse(["response"=>"ok"]);
        }

        return new Response(["response"=>"denied"]);
    }
}