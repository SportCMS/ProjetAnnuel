<?php ob_start(); ?>

<div style="display:flex; align-items:center">
    <h1>Gérer mes articles(<?= count($all_article) ?>)</h1>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a style="text-decoration:none;border-radius:50%; background:grey;padding:5px 10px;color:white" href="/ecrire-un-article">+</a>
</div>

<?php if (isset($all_article)) : ?>

    <table class="table">
        <thead>
            <tr>
                <th>title</th>
                <th>contenu</th>
                <th>date de création</th>
                <th>visualiser online</th>
                <th>editer</th>
                <th>supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_article as $article => $value) : ?>
                <tr>
                    <td><?= ucfirst($value['title']) ?></td>
                    <td><?= substr($value['content'], 0, 50) . '...' ?></td>
                    <td><?= substr($value['created_at'], 0, 10) ?></td>
                    <td>
                        <a href="/article?slug=<?= $value['slug'] ?>">
                            <img src="/public/assets/images/eye.svg" alt="" width="20" height="20">
                        </a>
                    </td>
                    <td>
                        <a href="/editer-un-article?slug=<?= $value['slug'] ?>">
                            <img src="/public/assets/images/edit.svg" alt="" width="20" height="20">
                        </a>
                    </td>
                    <td>
                        <a href="/deleteArticle?id=<?= $value['id'] ?>" onclick="confirm('voulez-vous supprimer l\'article ?')">
                            <img src="/public/assets/images/trash.svg" alt="" width="20" height="20">
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif; ?>


<style>
    th,
    td {
        padding: 15px;
        border: 1px solid grey;
        margin: 0;
        text-align: center;
    }

    table {
        width: 100%;
    }

    thead,
    tbody,
    table {
        border-collapse: collapse;
    }
</style>

<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>