<h5>Créer une page de blog</h5>
<span style="color:green"><?= isset($success) ? $success :  '' ?></span>
<form action="" method="POST">
    <input type="text" name="page_title" required placeholder="titre de la page" required /><br>
    <select name="type" id="page_type" required>
        <option value="">choisir une page</option>
        <option value="presentation">Page présentation</option>
        <option value="article">Page blog</option>
        <option value="contact">Page contact</option>
        <option value="about">page 'A propos'</option>
    </select>
    <br>
    <select name="page_role" required>
        <option value="">role autorisé pour cette page</option>
        <option value="admin">admin</option>
        <option value="user">user</option>
        <option value="public">public</option>
    </select>
    <br>
    <button type="submit" name="submit_add_page">valider</button>
</form>
<span><?= isset($message) ? $message : '' ?></span>
