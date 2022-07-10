<?php ob_start(); ?>


<h1 style="text-align:center;padding:15px 0">Articles front</h1>

<div class="gallery">
    <?php foreach ($all_article as $article => $value) : ?>
        <div class="gallery-item">
            <a href="/article?slug=<?= $value['slug'] ?>" style="text-decoration:none;color:grey">
                <figure style="border:1px solid lightgrey;margin-bottom:-15px">
                    <?php if (strstr($value['image'], 'https')) : ?>
                        <img src="<?= $value['image'] ?>" alt="card_image" />
                    <?php else : ?>
                        <img src="public/assets/img/<?= $value['image'] ?>" alt="card_image" />
                    <?php endif ?>
                </figure>
                <div style="border:1px solid lightgrey; padding:15px;background-color:white;text-align:center">
                    <h5 style="text-align:center"><?= $value['title'] ?></h5>
                    <small style="font-size:11px"><?= substr($value['created_at'], 0, 10) ?></small>
                </div>
            </a>
        </div>
    <?php endforeach ?>
</div>


<style>
    .separator {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .gallery-item:hover .masque_th_div {
        display: block;
    }

    /*****/

    .gallery {
        width: 90%;
        margin: 0 auto;
        column-count: 5;
        font-family: arial;
    }

    .gallery-item {
        break-inside: avoid;
        margin-bottom: 16px;
        border-radius: 8px;
    }

    .figure {
        margin: 0;
        position: relative;
    }

    .gallery-item img {
        width: 100%;
    }

    .gallery-item p {
        margin: 0;
        padding: 8px;
    }
</style>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>