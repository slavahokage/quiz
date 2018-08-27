<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\AdminQuizTable;
use App\Entity\QuizTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',TextType::class,array('attr'=>array('class'=>'form-control')));
        $builder->add('description',TextareaType::class,array('attr'=>array('class'=>'form-control my-2')));


        $builder->add('Question', CollectionType::class, array(
            'entry_type' => QuestionType::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'label' => false,
        ));
        $builder->add('save', SubmitType::class, array('label' => 'Go to answers page', 'attr' => array('class' => 'btn btn-primary mt-3')));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AdminQuizTable::class,
        ));
    }

}