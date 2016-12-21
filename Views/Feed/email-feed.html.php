<html>

<?php foreach($feeds as $feed): ?>
    <div>
        <?php
            $conteudo = $feed->getDescription();
            $stringLength = strlen($conteudo);
            $conteudo = substr($conteudo, 0, $stringLength/2) .  '<a title="...leia mais" href="' . $feed->getLink() . '">...leia mais</a>';
        ?>
        <h2><a href="<?= $feed->getPublicId(); ?>" title="...leia mais"><?= $feed->getTitle() ?></a></h2>
        <p><?= $conteudo ?></p>
    </div>
<?php endforeach; ?>

</html>