<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.09.2018
 * Time: 13:59
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditAccountController extends AbstractController
{
    /**
     * @Route("/edit", name="edit")
     * @param User $user
     *
     * @return Response
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig');
        }

        $form = $this->createForm(EditUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userName = $form->getData()->getUsername();
            $duplicate = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $userName]);
            if ($duplicate !== null) {
                $this->addFlash('error', 'Duplicate name! Сhoose another');

                return $this->redirectToRoute('edit');
            }

            $em = $this->getDoctrine()->getManager();

            $password = $passwordEncoder->encodePassword($user, $form->getData()->getPlainPassword());
            $user->setPassword($password);
            $user->setUsername($userName);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Successfully changed!');

            return $this->redirectToRoute('edit');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}