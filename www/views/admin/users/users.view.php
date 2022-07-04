<?php ob_start();

use App\core\Router;
?>


<h1>Gestion des membres</h1>
<section id="gestionmember">
    <table>

        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Status</th>
            <th>Date Update</th>
            <th>Update role</th>
            <th>Envoyer</th>
            <th>Supprimer</th>
        </tr>
        <tbody id="result">
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?= $user["lastname"]; ?></td>
                    <td><?= $user["firstname"]; ?></td>
                    <td><?= $user["email"]; ?></td>
                    <td><?= $user["status"] == 1 ? "actif" : "inactif" ?></td>
                    <td><?= (new \datetime($user["updated_at"]))->format('d-m-Y'); ?></td>

                    <td>

                        <form action="/editUserRole" method="POST">
                            <select id="role" name="role">
                                <option value="<?= $user["role"] ?>"><?= $user["role"] ?> </option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                                <option value="public">Public</option>
                            </select>
                    </td>
                    <td>
                        <div class="updatebutton">
                            <button type="submit" onclick="confirm('Confirmer la modification du rôle ?')">Update</button>
                        </div>
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">

                        </form>
                    </td>
                    <td>
                        <div class="supprimebutton">
                            <a href="/deleteUser?id=<?= $user['id'] ?>" onclick="confirm('Confirmer la suppression ?')">Supprimer</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</section>

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
<?php include('./views/admin/base/base.php'); ?>