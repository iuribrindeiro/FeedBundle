<?php
    /** @var \MauticPlugin\FeedBundle\Entity\Feed $feed */
    $feed;
    $view->extend('MauticCoreBundle:Default:slim.html.php');
?>
<div class="pa-20 mautibot-error">
    <div class="row mt-lg pa-md">
        <img class="col-xs-8 col-md-12 col-mn-12 col-sm-12" style="transform: scale(0.4)" src="/<?= $feed->getLogoEmail()->getPathname() ?>" alt="">
        <div class="mautibot-content col-xs-12 col-md-12">
            <blockquote class="np break-word">
                <h1>Deseja realmente cancelar sua inscrição em nosso feed?</h1>
            </blockquote>
            <h4 class="mt-5">
                <strong>
                    Você será removido da lista de emails do "<?= $feed->getName(); ?>" e não receberá mais notificações de novos artigos no nosso blog
                </strong>
            </h4>
            <div class="mt-5">
                <a href="<?= $url ?>" class="btn btn-success">Confirmar</a>
                <a href="<?= $url ?>" class="btn btn-warning">Cancelar</a>
            </div>
        </div>
    </div>
</div>