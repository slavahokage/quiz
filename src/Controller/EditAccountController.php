<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPhoto;
use App\Form\EditUserType;
use App\Traits\GoogleClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditAccountController extends AbstractController
{
    use GoogleClient;
    /**
     * @Route("/edit", name="edit")
     * @param User $user
     *
     * @return Response
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $userPhoto = $user->getUserPhoto();
        if ($userPhoto === null){
            $userPhoto = 'https://static.thenounproject.com/png/17241-200.png';
        } else {
            $userPhoto = 'https://drive.google.com/uc?id=' . $userPhoto->getSource();
        }

        if($user->getIsActive() == 0){
            return $this->render('user/ban.html.twig', array('userPhoto' => $userPhoto  ));
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

            if (isset($_FILES['photo'])) {
                if ($_FILES['photo']['error'] == 0) {

                    $fileType = $_FILES['photo']['type'];
                    if ($fileType !== 'image/jpeg' && $fileType !== 'image/png') {
                        $this->addFlash('error', 'Not valid photo format!');

                        return $this->redirectToRoute('edit');
                    }

                    $photo = $user->getUserPhoto();

                    if ($photo === null){
                        $photo = new UserPhoto();
                        $user->setUserPhoto($photo);
                    }

                    $uploadFile = $this->uploadFile($_FILES['photo']);
                    $photo->setMime($_FILES['photo']['type']);
                    $photo->setRealName($_FILES['photo']['name']);
                    $pathParts = pathinfo($_FILES["photo"]["name"]);
                    $photo->setExtension($pathParts['extension']);
                    $photo->setSource($uploadFile);
                }
            }

            $password = $passwordEncoder->encodePassword($user, $form->getData()->getPlainPassword());
            $user->setPassword($password);
            if (!empty($userName)){
                $user->setUsername($userName);
            }
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Successfully changed!');

            return $this->redirectToRoute('edit');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'userPhoto' => $userPhoto
        ]);
    }
}