<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.09.2018
 * Time: 13:59
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditAccount extends AbstractController
{
    /**
     * @Route("/edit", name="edit")
     *
     */
    public function index(Request $request) : Response
    {

    }
}