<h1>Tableau de bord</h1>

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