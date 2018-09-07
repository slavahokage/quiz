<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserResultsController extends AbstractController
{
    /**
     * @Route("/results", name="resultsU")
     */
    public function index()
    {
        return $this->render('user/user_results.html.twig');
    }
}