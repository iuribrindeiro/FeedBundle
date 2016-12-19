<?php

/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 19/12/16
 * Time: 13:32
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FeedType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'id',
                HiddenType::class,
                [
                    'attr' => [
                        'value' => $options['data']['id']
                    ]
                ]
            )->add(
                'name',
                TextType::class,
                [
                    'attr' => [
                        'value' => $options['data']['name']
                    ],
                    'required' => true
                ]
            )->add(
                'segments',
                EntityType::class,
                [
                    'class' => 'LeadBundle:LeadList',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->orderBy('s.id', 'ASC');
                    },
                    'multiple' => true,
                    'choice_label' => 'Segments',
                    'required' => true,
                ]
            )
            ->add(
                'email',
                EntityType::class,
                [
                    'class' => 'EmailBundle:Email',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.id', 'ASC');
                    },
                    'choice_label' => 'Email',
                    'required' => true,
                ]
            );
    }

    public function getName()
    {
        return 'feed_type';
    }
}