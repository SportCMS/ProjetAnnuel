<h1>Affichage articles</h1>

<?php if(isset($all_article)) : ?>
    <?php foreach ($all_article as $article => $value) : ?>
        <p><?= $value['title'] ?></p>
    <?php endforeach; ?>
<?php endif; ?>
