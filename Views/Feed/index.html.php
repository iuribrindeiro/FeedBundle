<?php
$view->extend('MauticCoreBundle:Default:content.html.php');
//$view['slots']->set('mauticContent', 'campaign');
$view['slots']->set('headerTitle', 'Feeds');
$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:page_actions.html.php',
        [
            'templateButtons' => [
                'new' => $permissions['feed:feeds:create'],
            ],
            'routeBase' => 'feed',
        ]
    )
);
?>
<div class="panel panel-default bdr-t-wdh-0">
    <?php echo $view->render('MauticCoreBundle:Helper:list_toolbar.html.php', [
        'searchValue' => $searchValue,
        'searchHelp'  => 'mautic.core.help.searchcommands',
        'action'      => $currentRoute,
        'filters'     => $filters,
    ]); ?>

    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>
</div>