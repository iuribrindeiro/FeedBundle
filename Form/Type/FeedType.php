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
use Mautic\CoreBundle\Form\EventListener\FormExitSubscriber;
use Mautic\EmailBundle\Entity\Email;
use Mautic\LeadBundle\Entity\LeadList;
use MauticPlugin\FeedBundle\Entity\Feed;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                        'value' => $options['data']->getId()
                    ]
                ]
            )->add(
                'name',
                TextType::class,
                [
                    'attr' => [
                        'value' => $options['data']->getName(),
                        'class' => 'form-control'
                    ],
                    'label' => 'Nome',
                    'label_attr' => ['class' => 'control-label'],
                    'required' => true,
                ]
            )->add(
                'urlFeed',
                UrlType::class,
                [
                    'attr' => [
                        'value' => $options['data']->getUrlFeed(),
                        'class' => 'form-control',
                    ],
                    'required' => true,
                    'label' => 'Url Feed',
                    'label_attr' => ['class' => 'control-label'],
                ]
            )->add(
                'leadLists',
                EntityType::class,
                [
                    'class' => LeadList::class,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->orderBy('s.id', 'ASC');
                    },
                    'multiple' => true,
                    'label' => 'Segmentos',
                    'label_attr' => ['class' => 'control-label'],
                    'choice_label' => 'Name',
                    'required' => true,
                ]
            )
            ->add(
                'email',
                EntityType::class,
                [
                    'class' => Email::class,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.id', 'ASC');
                    },
                    'choice_label' => 'Name',
                    'label' => 'Email',
                    'label_attr' => ['class' => 'control-label'],
                    'required' => true,
                ]
            );

        $builder->add('buttons', 'form_buttons');
    }

    public function setDefaultOptions(OptionsResolverInterface $optionsResolver)
    {
        $optionsResolver->setDefaults([
            Feed::class
        ]);
    }

    public function getName()
    {
        return 'feed';
    }
}