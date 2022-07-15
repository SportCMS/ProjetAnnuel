<?php ob_start(); ?>

<div style="display:flex; align-items:center">
    <h1>Gérer mes articles(<?= count($all_article) ?>)</h1><br><br>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a style="text-decoration:none;border-radius:50%; background:grey;padding:3px 10px;color:white" href="/ecrire-un-article">+</a>
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
<nav>
    <ul class="pagination">
        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
            <?php if ($currentPage > 1) : ?>
                <a href="/gerer-mes-articles?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
            <?php else : ?>
                <span class="page-link" style="opacity:0.3;cursor:pointer">Précédente</span>
            <?php endif ?>
        </li>

        <?php for ($i = 1; $i <= $pages; $i++) : ?>
            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
            <li class="page-item">
                <a href="/gerer-mes-articles?page=<?= $i ?>" class="<?= ($currentPage == $i) ? " activeLink" : "page-link " ?>"><?= $i ?></a>
            </li>
        <?php endfor ?>
        <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
        <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
            <?php if ($currentPage >= $pages) : ?>
                <span class="page-link" style="opacity:0.3;cursor:pointer">Suivante</span>
            <?php else : ?>
                <a href="/gerer-mes-articles?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
            <?php endif ?>
        </li>
    </ul>
</nav> 
<style>
    th,
    td {
        padding: 10px;
        border: 1px solid grey;
        margin: 0;
        text-align: center;
    }

    table {
        width: 100%;
        background: white;
    }

    thead,
    tbody,
    table {
        border-collapse: collapse;
    }

    .pagination {
        display: flex;
        align-items: center;
        width: 50%;
        padding: 10px 0;
        margin: 10px auto;
    }

    .pagination .page-link {
        padding: 8px;
        background: white;
        text-decoration: none;
        color: black;
    }

    .activeLink {
        padding: 8px;
        text-decoration: none;
        color: purple;
        background: lightgrey;
    }
</style>

<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>  