<?php
/**
 * Created by PhpStorm.
 * User: vyacheslav
 * Date: 4/27/19
 * Time: 11:54
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['attr' => array('placeholder' => 'Username  ')])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password', 'attr' => array('placeholder' => 'Password')),
                'second_options' => array('label' => 'Repeat Password','attr' => array('placeholder' => 'Repeat Password'))
            ))
            ->add('send', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary mt-3')));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}