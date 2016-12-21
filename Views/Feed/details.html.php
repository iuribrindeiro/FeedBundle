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
                'edit'   => $permissions['campaign:campaigns:edit'],
                'clone'  => $permissions['campaign:campaigns:create'],
                'delete' => $permissions['campaign:campaigns:delete'],
                'close'  => $permissions['campaign:campaigns:view'],
            ],
            'routeBase' => 'feed',
        ]
    )
);
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ campaign detail collapseable -->
        </div>

        <div class="bg-auto bg-dark-xs">
            <!-- some stats -->
            <div class="pa-md">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ stats -->
        </div>
    </div>

</div>
<!--/ end: box layout -->
