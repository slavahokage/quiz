<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxGridController extends AbstractController
{

    /**
     * @Route("/ajaxGrid", name="ajax_grid")
     *
     */
    public function index():Response
    {

        return $this->render('ajaxGrid/ajaxgrid.html.twig');
    }
}