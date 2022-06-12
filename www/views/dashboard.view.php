


<?php /*$this->includePartial("form", $user->getLogoutBtn());*/?>


<h1>Tableau de bord</h1>
<<<<<<< HEAD

<div class="report-notifications" style="position:relative">
        <img src="public/assets/images/bell.svg" alt="" width="25" height="25">
        <?php if ($_SESSION['report'] > 0) : ?>
            <a href="/reports">
                <span style="display:block;padding:2px 7px;border-radius:50%;color:white;background:red;position:absolute;top:-4px;left:15px">
                    <?= $_SESSION['report'] ?>
                </span>
            </a>
        <?php endif  ?>
</div>
=======
<?php if (false) : ?>
    <h2>Salut</h2>
    <!--Comment faire un if dans une vue-->
<?php endif; ?>

<hr>
<h1>Gérer mes pages</h1>
<a href="/addPage">Ajouter une page</a>

<h5>Liste de mes pages</h5>
<?php foreach ($pages as $page) : ?>
    <div class="page_edit">
        <span>
            <a href="<?= $page['link'] ?>"><?= $page['title'] ?></a>
        </span>
        &nbsp;
        <span>
            <a href="/editPage?page=<?= $page['title'] ?>">
                <img src="/public/assets/images/edit.svg" alt="" width="20" height="20" />
            </a>
        </span>
        &nbsp;
        <span>
            <a href="/deletePage?page=<?= $page['title'] ?>&id=<?= $page['id'] ?>" onclick="confirm('confirmer la suppression?')">
                <img src="/public/assets/images/trash.svg" alt="" width="20" height="20" />
            </a>
        </span>
    </div>
<?php endforeach ?>
<hr>
<h1>Gérer mon menu</h1>
<a href="/editMenu">Get started</a>
>>>>>>> feature/slug
