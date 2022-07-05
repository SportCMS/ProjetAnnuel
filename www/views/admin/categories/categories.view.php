<?php ob_start(); ?>

<div style="display:flex; align-items:center">
    <h1>Gérer mes catégories</h1><br><br>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a style="text-decoration:none;border-radius:50%; background:grey;padding:5px 10px;color:white" href="/crer-une-categorie">+</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Nom</th>
            <th>texte</th>
            <th>image</th>
            <th>Visualier online</th>
            <th>supprimer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category) : ?>
            <tr>
                <td><?= $category['name'] ?></td>
                <td><?= $category['description'] ?></td>
                <td><img src="/public/uploads/<?= $category['image'] ?>" alt="" width="60" height="60"></td>
                <td>
                    <a href="/<?= $category['slug'] ?>">
                        <img src="/public/assets/img/eye.svg" alt="" width="20" height="20">
                    </a>
                </td>
                <td>
                    <a href="/deleteCategory?id=<?= $category['id'] ?>" onclick="confirm('confirmer la suppression?')">
                        <img src="/public/assets/img/trash.svg" alt="" width="20" height="20">
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<style>
    th,
    td {
        padding: 15px;
        border: 1px solid grey;
        margin: 0;
        text-align: center;
    }

    thead,
    tbody,
    table {
        border-collapse: collapse;
    }
</style>

<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>