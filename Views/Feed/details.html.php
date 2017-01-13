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
                            <?php /** @var \MauticPlugin\FeedBundle\Entity\Feed $feed */
                            echo $view->render(
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
                                <div class="tab-content pa-md">
                                    <?php if($feed->getName()): ?>
                                        <?php foreach($feed->getArticles() as $article): ?>
                                            <div class="tab-pane fade bdr-w-0 active in">
                                                <ul class="list-group campaign-event-list">
                                                    <li class="list-group-item bg-auto bg-light-xs">
                                                        <!-- depois fazer calculode quantos foram cc  -->
<!--                                                        <div class="progress-bar progress-bar-success" style="width: 100%"></div>-->
                                                        <div class="box-layout">
                                                            <div class="col-md-1 va-m">
                                                                <h3>
                                                                    <span class="fa fa-rocket text-success"></span>
                                                                </h3>
                                                            </div>
                                                            <div class="col-md-7 va-m">
                                                                <h5 style="cursor: pointer" data-id-article="<?= $article->getId() ?>"
                                                                    name="hide-detalhes-article" class="fw-sb text-primary mb-xs">
                                                                    <?php /** @var \MauticPlugin\FeedBundle\Entity\Article $article */
                                                                    echo $article->getTitle(); ?>
                                                                </h5>
                                                                <em class="col-sm-2">Enviados: <?= isset($articleDetails[$article->getId()]['emailsEnviados']) ? $articleDetails[$article->getId()]['emailsEnviados'] : 0 ?></em>
                                                                <em class="col-sm-2">Abertos: <?= isset($articleDetails[$article->getId()]['emailsLidos']) ? $articleDetails[$article->getId()]['emailsLidos'] : 0 ?></em>
                                                                <em class="col-sm-2">Cliques: <?= isset($articleDetails[$article->getId()]['emailsClicados']) ? $articleDetails[$article->getId()]['emailsClicados'] : 0 ?></em>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item bg-auto bg-light-xs hidden" data-id-article="<?= $article->getId() ?>" name="detalhes-article">
                                                            <?php /** @var \Mautic\EmailBundle\Entity\Stat $stat */
                                                            foreach($article->getStats() as $stat): ?>
                                                                <?php if(isset($hits[$stat->getLead()->getId()])) {
                                                                    $class = 'success';
                                                                }elseif($stat->isRead()) {
                                                                    $class = 'info';
                                                                }else {
                                                                    $class = 'warning';
                                                                }?>
                                                                <div class="alert alert-<?= $class  ?>">
                                                                    <div class="col-sm-4">
                                                                        <label>Cliente:</label>
                                                                        <a href="<?php echo $view['router']->path(
                                                                            'mautic_contact_action',
                                                                            ['objectAction' => 'view', 'objectId' => $stat->getLead()->getId()]
                                                                        ); ?>" data-toggle="ajax">
                                                                            <?php echo $stat->getEmailAddress() ?>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label>Aberto:</label>
                                                                        <?= $stat->isRead() ? 'Sim' : 'Não'?>
                                                                    </div>
                                                                    <div>
                                                                        <label>Acessado:</label>
                                                                        <?= isset($hits[$stat->getLead()->getId()]) ? $hits[$stat->getLead()->getId()] : $hits[$stat->getLead()->getId()] ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
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
            </div>
            <!--/ stats -->
        </div>
    </div>

</div>

<script>
    function clickDetalhesArticle() {

    }
    var elements = document.querySelectorAll('h5[name="hide-detalhes-article"]');

    for(var element in elements) {
        element = parseInt(element);
        if(element >= 0) {
            elements[element].onclick = function (e) {
                var idArticle = this.getAttribute('data-id-article');
                articleElements = document.querySelectorAll('div[name="detalhes-article"]');

                for(var item in articleElements) {
                    if(item >= 0) {
                        articleElements[item].classList.add('hidden');
                    }
                }
                document.querySelector('div[name="detalhes-article"][data-id-article="' + idArticle + '"]').classList.remove('hidden');
            }
        }
    }
</script>
<!--/ end: box layout -->
