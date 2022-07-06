<?php ob_start(); ?>

<h1>Sitemap</h1>

<br>
<span><?= htmlspecialchars("<?xml version='1.0' encoding='UTF-8'?>") ?></span><br>
<span><?= htmlspecialchars("<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' xmlns:xhtml='http://www.w3.org/1999/xhtml' xmlns:image='http://www.google.com/schemas/sitemap-image/1.1' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'>") ?></span><br>

<span class="one"><?= htmlspecialchars("<url>") ?></span><br>
<span class="two"> <?= htmlspecialchars("<loc>http://" . $domain . "/bienvenue</loc>") ?></span><br>
<span class="two">
    <?= htmlspecialchars("<changefreq>daily</changefreq>") ?>
</span><br>
<span class="two">
    <?= htmlspecialchars("<priority>1.0</priority>") ?>
</span><br>
<span class="one"><?= htmlspecialchars("</url>") ?></span><br>

<?php foreach ($pages as $page) : ?>
    <span class="one"><?= htmlspecialchars("<url>") ?></span><br>
    <span class="two"> <?= htmlspecialchars("<loc>http://" . $domain .  $page['link'] . "</loc>") ?></span><br>
    <span class="three">
        <?= isset($page['updated_at'])
            ?  htmlspecialchars("<lastmod>" . (new \Datetime($page['updated_at']))->format('Y-m-d' . 'T' . 'H:i:s' . 'Z') . "</lastmod>")
            :  htmlspecialchars("<lastmod>" . (new \Datetime($page['created_at']))->format('Y-m-d' . 'T' . 'H:i:s' . 'Z') . "</lastmod>") ?>
    </span><br>
    <span class="three">
        <?= htmlspecialchars("<changefreq>daily</changefreq>") ?>
    </span><br>
    <span class="three">
        <?= htmlspecialchars("<priority>1.0</priority>") ?>
    </span><br>
    <span class="two"><?= htmlspecialchars("</url>") ?></span><br>
<?php endforeach ?>
<br>
<?php foreach ($categories as $category) : ?>
    <span class="two"><?= htmlspecialchars("<url>") ?></span><br>
    <span class="three"> <?= htmlspecialchars("<loc>https://" . $domain . '/' . $category['slug'] . "</loc>") ?></span><br>
    <span class="three">
        <?= isset($category['updated_at'])
            ?  htmlspecialchars("<lastmod>" . (new \Datetime($category['updated_at']))->format('Y-m-d' . 'T' . 'H:i:s' . 'Z') . "</lastmod>")
            :  htmlspecialchars("<lastmod>" . (new \Datetime($category['created_at']))->format('Y-m-d' . 'T' . 'H:i:s' . 'Z') . "</lastmod>") ?>
    </span><br>
    <span class="three"> <?= htmlspecialchars("<image:image>") ?></span><br>
    <span class="four">
        <?= htmlspecialchars("<image:loc>https://" . $domain .  $category['image'] . "</image:loc>") ?>
    </span><br>
    <span class="three"> <?= htmlspecialchars("</image:image>") ?></span><br>
    <span class="three">
        <?= htmlspecialchars("<changefreq>daily</changefreq>") ?>
    </span><br>
    <span class="three">
        <?= htmlspecialchars("<priority>1.0</priority>") ?>
    </span><br>
    <span class="two"><?= htmlspecialchars("</url>") ?></span><br>
<?php endforeach ?>
<br>
<?php foreach ($articles as $article) : ?>
    <span class="two"><?= htmlspecialchars("<url>") ?></span><br>
    <span class="three"> <?= htmlspecialchars("<loc>https://" . $domain . '/article?slug=' . $article['slug'] . "</loc>") ?></span><br>
    <span class="three">
        <?= isset($article['updated_at'])
            ?  htmlspecialchars("<lastmod>" . (new \Datetime($article['updated_at']))->format('Y-m-d' . 'T' . 'H:i:s' . 'Z') . "</lastmod>")
            :  htmlspecialchars("<lastmod>" . (new \Datetime($article['created_at']))->format('Y-m-d' . 'T' . 'H:i:s' . 'Z') . "</lastmod>") ?>
    </span><br>
    <span class="three">
        <?= htmlspecialchars("<changefreq>daily</changefreq>") ?>
    </span><br>
    <span class="three">
        <?= htmlspecialchars("<priority>1.0</priority>") ?>
    </span><br>
    <span class="three"> <?= htmlspecialchars("<image:image>") ?></span><br>
    <span class="four">
        <?= strstr($article['image'], 'https') == true
            ?  htmlspecialchars("<image:loc>" .  $article['image'] . "</image:loc>")
            : htmlspecialchars("<image:loc>https://" . $domain . '/' .  $article['image'] . "</image:loc>")
        ?>
    </span><br>
    <span class="three"> <?= htmlspecialchars("</image:image>") ?></span><br>
    <span class="two"><?= htmlspecialchars("</url>") ?></span><br>
    <span class="two"><?= htmlspecialchars("</urlset>") ?></span><br>
<?php endforeach ?>


<style>
    .one {
        margin-left: 30px;
    }

    .two {
        margin-left: 60px;
    }

    .three {
        margin-left: 90px;
    }

    .four {
        margin-left: 120px;
    }
</style>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>