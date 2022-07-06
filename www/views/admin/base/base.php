<!DOCTYPE html>
<html lang="fr">

<?php include_once './views/admin/partials/head.php' ?>

<?php include_once './views/admin/partials/header.php' ?>

<body id="body-pd">
  <main style="min-height:58vh; padding: 100px 20px 50px 20px; margin-bottom: 10%;">

    <?= $content ?>

  </main>

  <?php include_once './views/admin/partials/footer.php' ?>

  <script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>
  <script src="src/js/script.js"></script>
  <script src="public/src/js/search.js"></script>
</body>

</html>

<style>
  .nav__link {
    text-decoration: none;
    color: black;
  }

  .active-link {
    border-bottom: 2px solid lightgreen;
  }
</style>