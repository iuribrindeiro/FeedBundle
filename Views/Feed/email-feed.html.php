<html>
<?php /** @var \MauticPlugin\FeedBundle\Entity\Feed $objFeed */ ?>
<?php $url = $view['router']->path('mautic_public_unsubscribe_index', ['feedId' => $objFeed->getId()]) ?>
<?php foreach($feeds as $feed): ?>
    <div style="background-color: #e8e8e8; padding-top: 1%; padding-bottom: 3%">
        <div style="background-color: white; margin-left: 20%; margin-right: 20%;">
            <?php
                $conteudo = $feed->getDescription();
                $stringLength = strlen($conteudo);
                $conteudo = substr($conteudo, 0, $stringLength/2);
            ?>
            <h2 style="padding: 2%;">
                <a href="<?= $feed->getPublicId(); ?>" title="...leia mais" style="color:deepskyblue">
                    <span style="color:dodgerblue">
                        <?= mb_strtoupper($feed->getTitle()); ?>
                    </span>
                </a>
            </h2>
            <div style="padding: 2%; font-size: 130%;"><?= $conteudo ?>
                <a style="color: deepskyblue;" href="<?=$feed->getLink()?>">...[leia mais]</a>
            </div>
            <div>
                <small>Caso não queira mais receber esses emails, clique <a href="<?= $url ?>">aqui</a></small>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</html>