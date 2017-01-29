<html>

<?php foreach($feeds as $feed): ?>
    <div style="background-color: #e8e8e8">
        <div style="background-color: white; margin-left: 12%; margin-right: 12%">
            <?php
                $conteudo = $feed->getDescription();
                $stringLength = strlen($conteudo);
                $conteudo = substr($conteudo, 0, $stringLength/2) .  '...<a title="...leia mais" href="' . $feed->getLink() . '">[leia mais]</a>';
            ?>
            <h2 style="padding-left: 10%; padding-right: 10%"><a href="<?= $feed->getPublicId(); ?>" title="...leia mais"><?= strtoupper($feed->getTitle()); ?></a></h2>
            <p style="text-align: left; margin-top: 2%; margin-right: 4%; margin-left: 4%; margin-bottom: 2%;"><?= $conteudo ?></p>
        </div>
    </div>
<?php endforeach; ?>

</html>