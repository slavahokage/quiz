<?php

declare(strict_types=1);

namespace App\Controller;


use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);


        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                try {
                    $entityManager = $this->getDoctrine()->getManager();
                    $user->setIsActive(1);
                    $entityManager->persist($user);
                    $entityManager->flush();
                }catch (\Exception $e){
                    return $this->render(
                        'registration/registration.html.twig',
                        array('form' => $form->createView(), 'error' => 'Something went wrong! This login or email is busy.')
                    );
                }

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $this->get('session')->set('_security_main', serialize($token));

                return $this->redirectToRoute('quiz_list');
            }

        if($form->isSubmitted() && !$form->isValid()){
            return $this->render(
                'registration/registration.html.twig',
                array('form' => $form->createView(), 'error' => 'Something went wrong! You did not pass the validation.')
            );
        }
        return $this->render(
            'registration/registration.html.twig',
            array('form' => $form->createView())
        );
    }
}