<?php ob_start(); ?>


<h1 style="padding:20px 0;text-align:center">Catégorie : <?= ucfirst($category->getName()) ?></h1>
<div style="text-align:center">
    <img src="public/uploads/<?= $category->getImage() ?>" alt="" style="max-width:80%">
</div>
<p style="padding:20px 0"><?= $category->getDescription() ?></p><br>
<hr>

<h1>Les articles associés</h1>
<div style="display:flex;flex-flow:row wrap; justify-content:center;padding:20px">
    <?php foreach ($articles as $article) : ?>
        <div style="width:20%;min-height:9vh;border:1px solid grey;margin:10px;padding:5px">
            <h5><?= $article['title'] ?></h5>
            <p><?= substr($article['content'], 0, 250) ?>....</p>
            <span><a href="/article?slug=<?= $article['slugArticle'] ?>">Lire la suite</a></span>
        </div>
    <?php endforeach ?>
</div>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>