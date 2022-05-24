<h1>Poster un article</h1>

<?php $this->includePartial("form", $article->getArticleForm());?>

 <!-- envoie des erreurs -->
 <?php if(isset($result)) : ?>
        <?php foreach ($result as $key => $value) : ?>
            <p><?= $value ?></p>
        <?php endforeach; ?>
    <?php endif; ?>



				