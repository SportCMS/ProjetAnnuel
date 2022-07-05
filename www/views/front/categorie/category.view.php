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
        <div class="thumbnails_div" style="max-width:280px;min-height:9vh;border:1px solid grey;margin:10px;object-fit:cover;">
            <a href="">
                <?php if (strstr($article['artImage'], 'https')) : ?>
                    <img src="<?= $article['artImage'] ?>" alt="" style="width:274px;height:100%;">
                <?php else : ?>
                    <img src="public/assets/img/<?= $article['artImage'] ?>" alt="">
                <?php endif ?>
                <div class="masque_th_div">
                    <div class="text_masque_th_div">
                        <small>Posté par admin</small>
                        <br><br>
                        <h4><?= ucfirst($article['title']) ?></h4>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach ?>
</div>

<style>
    .thumbnails_div {
        position: relative;
    }

    .masque_th_div {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
    }

    .text_masque_th_div {
        text-align: center;
        color: white;
        font-size: 18px;
        position: absolute;
        top: 20%;
        left: 20%;
        right: 20%;
    }

    .thumbnails_div img {
        object-fit: cover;
        transition: transform .3s;
    }

    .thumbnails_div:hover .masque_th_div {
        /* transition: 1s; */
        display: block;
    }
</style>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>