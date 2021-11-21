<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints'=>[
                    new NotBlank(['message' => 'Please enter a title.']),
//                    new Regex([
//                        'pattern' => '/^[a-z0-9-_\.\,]+$/i',
//                        'message' => 'The title can only contain alphanumeric characters, undescores, periods and commas.'
//                    ]),
                    new length([
                        'max'=> 50,
                        'maxMessage' => 'The title cannot contain more than 40 characters'
                    ])
                ]
            ])
            ->add('note', TextareaType::class, [
                'required'=>false,
                'constraints'=>[
//                    new Regex([
//                        'pattern' => '/^[a-z0-9-_\.\,]+$/i',
//                        'message' => 'The description can only contain alphanumeric characters, underscores, periods and commas.'
//                    ]),
                    new length([
                        'max'=> 500,
                        'maxMessage' => 'Description cannot contain more than 500 characters'
                    ])
                ]
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'To do' => 'todo',
                    'In progress' => 'inprogress',
                    'Done' => 'done',
                ],
            ])
            ->add('dueTo',DateTimeType::class,[
                    'widget' => 'single_text'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
