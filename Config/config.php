<?php

return [
    'name'        => 'Feed Bundle',
    'description' => 'Create a feed with a list of clients',
    'version'     => '1.4',
    'author'      => 'Iuri',

    'routes' => [
        'main' => [
            'mautic_feed_index' => [
                'path' => '/feeds',
                'controller' => 'FeedBundle:Feed:index'
            ],
            'mautic_feed_action' => [
                'path' => '/feeds/{objectAction}/{objectId}',
                'controller' => 'FeedBundle:Feed:execute',
                'method' => 'POST'
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
                ]
            ]
        ]
    ]
];