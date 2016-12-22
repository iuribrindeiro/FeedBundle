<?php

return [
    'name'        => 'Feed Bundle',
    'description' => 'Create a feed with a list of clients',
    'version'     => '2.1',
    'author'      => 'Iuri',


    'menu' => [
        'main' => [
            'items' => [
                'Feeds' => [
                    'priority' => 4,
                    'route' => 'mautic_feed_index',
                    'iconClass' => 'fa-users'
                ]
            ]
        ]
    ],
    'routes' => [
        'main' => [
            'mautic_feed_index' => [
                'path' => '/feeds',
                'controller' => 'FeedBundle:Feed:index'
            ],
            'mautic_feed_action' => [
                'path' => '/feeds/{objectAction}/{objectId}',
                'controller' => 'FeedBundle:Feed:execute'
            ]
        ]
    ],
    'services' => [
        'models' => [
            'mautic.feed.model.feed' => [
                'class' => \MauticPlugin\FeedBundle\Model\FeedModel::class,
                'arguments' => [
                    'mautic.lead.model.list',
                    'mautic.form.model.form',
                    'mautic.email.model.email',
                    'templating'
                ]
            ]
        ],
        'forms' => [
            'mautic.feed.type.form' => [
                'class'     => \MauticPlugin\FeedBundle\Form\Type\FeedType::class,
                'alias'     => 'feed',
            ],
        ]
    ]
];