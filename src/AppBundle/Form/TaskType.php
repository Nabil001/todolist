<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $listener = function (FormEvent $event) {
            $task = $event->getData();

            if (null === $task->getAuthor()) {
                $event->getForm()->add('isAnonymous', CheckboxType::class, array(
                    'label' => 'Publier anonymement',
                    'required' => false,
                    'mapped' => false
                ));
            } else {
                $event->getForm()->add('author', AuthorType::class, array(
                    'label' => false,
                    'disabled' => false
                ));
            }
        };

        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre'
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'Contenu'
            ))
            ->addEventListener(FormEvents::PRE_SET_DATA, $listener)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task',
        ));
    }
}
