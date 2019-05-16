<?php
/**
 * Created by PhpStorm.
 * User: vyacheslav
 * Date: 3/7/19
 * Time: 1:17 PM
 */

namespace App\Controller;


use App\Entity\Article;
use App\Entity\QuizTable;
use App\Entity\ResultOfQuiz;
use App\Form\ArticleType;
use App\Traits\GoogleClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    use GoogleClient;

    /**
     * @Route("/articles", name="article_list")
     *
     */
    public function articles()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig');
        }

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('articles/listOfArticles.html.twig', array('articles' => $articles, 'user' => $user));
    }

    /**
     * @Route("/admin/createArticle", name="create_article")
     *
     */
    public function createArticle(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $properties = $form->getData();
            $file = $form['source']->getData();

            $article = new Article();

            $upload = ['name' => $file->getClientOriginalName(), 'tmp_name' => $file->getPathname(), 'type' => $file->getMimeType()];

            $article->setTitle($properties->getTitle());
            $article->setMime($upload['type']);
            $article->setRealName($upload['name']);
            $article->setExtension($file->guessExtension());
            $article->setSource($this->uploadFile($upload));

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article Created!');

            return $this->render('articles/createArticle.html.twig', [
                'form' => $form->createView(),
                'user' => $user
            ]);
        }

        return $this->render('articles/createArticle.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/download/{filename}", name="download_article")
     *
     */
    public function downloadArticle(Request $request, $filename)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig');
        }

        $file = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['source' => $filename]);

        $response = new StreamedResponse();
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $file->getRealName()
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', $file->getMime());
        $response->setCallback(function () use ($filename) {
            echo $this->downloadFile($filename);
            ob_flush();
            flush();
        });

        return $response;
    }

    /**
     * @Route("/delete/{filename}", name="delete_file")
     */
    public function delete($filename)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig');
        }

        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig');
        }

        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['source' => $filename]);


        $this->deleteFile($filename);
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->redirectToRoute('article_list', array('articles' => $articles, 'user' => $user));
    }
}