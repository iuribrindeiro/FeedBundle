<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'feed');
$view['slots']->set('headerTitle', $feed->getName());

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:page_actions.html.php',
        [
            'item'            => $feed,
            'templateButtons' => [
<<<<<<< HEAD
                'edit'   => $permissions['campaign:campaigns:edit'],
                'clone'  => $permissions['campaign:campaigns:create'],
                'delete' => $permissions['campaign:campaigns:delete'],
                'close'  => $permissions['campaign:campaigns:view'],
            ],
            'routeBase' => 'feed',
        ]
    )
);
=======
                'edit'   => $permissions['feed:feeds:edit'],
                'clone'  => $permissions['feed:feeds:create'],
                'delete' => $permissions['feed:feeds:delete'],
                'close'  => $permissions['feed:feeds:view'],
            ],
            'routeBase' => 'campaign',
        ]
    )
);
$view['slots']->set(
    'publishStatus',
    $view->render('MauticCoreBundle:Helper:publishstatus_badge.html.php', ['entity' => $feed])
);
>>>>>>> 64822f0fe8e02aea48fdb690a072c892cadbeac2
?>

<!-- start: box layout -->
<div class="box-layout">
    <!-- left section -->
    <div class="col-md-9 bg-white height-auto">
        <div class="bg-auto">

            <!-- campaign detail collapseable -->
            <div class="collapse" id="campaign-details">
                <div class="pr-md pl-md pb-md">
                    <div class="panel shd-none mb-0">
                        <table class="table table-bordered table-striped mb-0">
                            <tbody>
                            <?php echo $view->render(
                                'MauticCoreBundle:Helper:details.html.php',
                                ['entity' => $feed]
                            ); ?>
<<<<<<< HEAD
=======
                            <?php foreach ($sources as $sourceType => $typeNames): ?>
                            <?php if (!empty($typeNames)): ?>
                            <tr>
                                <td width="20%"><span class="fw-b">
                                    <?php echo 'testa isso aqui 1'; ?>
                                </td>
                                <td>
                                    <?php echo implode(', ', $typeNames); ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
>>>>>>> 64822f0fe8e02aea48fdb690a072c892cadbeac2
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ campaign detail collapseable -->
        </div>

        <div class="bg-auto bg-dark-xs">
<<<<<<< HEAD
=======
            <!-- campaign detail collapseable toggler -->
            <div class="hr-expand nm">
                <span data-toggle="tooltip" title="Detail">
                    <a href="javascript:void(0)" class="arrow text-muted collapsed" data-toggle="collapse"
                       data-target="#campaign-details"><span
                            class="caret"></span> <?php echo 'Details'; ?></a>
                </span>
            </div>
            <!--/ campaign detail collapseable toggler -->

>>>>>>> 64822f0fe8e02aea48fdb690a072c892cadbeac2
            <!-- some stats -->
            <div class="pa-md">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel">
<<<<<<< HEAD
                            <div class="pa-md">
                                <?php if($feed->getStatEmails()): ?>
                                    <?php foreach($feed->getStatEmails() as $statEmail): ?>
                                        <div class="tab-pane fade bdr-w-0 active in">
                                            <ul class="list-group campaign-event-list">
                                                <li class="list-group-item bg-auto bg-light-xs">
                                                    <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                                                    <div class="box-layout">
                                                        <div class="col-md-1 va-m">
                                                            <h3>
                                                                <span class="fa fa-rocket text-success"></span>
                                                            </h3>
                                                        </div>
                                                        <div class="col-md-7 va-m">
                                                            <h5 class="fw-sb text-primary mb-xs">
                                                                <?php echo $statEmail->getEmailAddress(); ?>
                                                            </h5>
                                                        </div>
                                                        <div class="col-md-4 va-m text-right">
                                                            <em class="text-white dark-sm">Aberto em <?= $statEmail->getDateRead() ?></em>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
=======
                            <div class="panel-body box-layout">
                                <div class="col-md-9 va-m">
                                    <?php echo $view->render(
                                        'MauticCoreBundle:Helper:graph_dateselect.html.php',
                                        ['dateRangeForm' => $dateRangeForm, 'class' => 'pull-right']
                                    ); ?>
                                </div>
                            </div>
                            <div class="pt-0 pl-15 pb-10 pr-15">
                                <?php echo $view->render(
                                    'MauticCoreBundle:Helper:chart.html.php',
                                    ['chartData' => $stats, 'chartType' => 'line', 'chartHeight' => 300]
                                ); ?>
>>>>>>> 64822f0fe8e02aea48fdb690a072c892cadbeac2
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ stats -->
        </div>
    </div>
<<<<<<< HEAD

=======
>>>>>>> 64822f0fe8e02aea48fdb690a072c892cadbeac2
</div>
<!--/ end: box layout -->
