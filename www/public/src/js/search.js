$(document).ready(function () {
    $('#search').on('click', () => {
        window.location.href = "/voir-les-utilisateurs"
    });
    $('#search').focus()

    $('#search').keyup(() => {
        $('#result').html('');
        let search = $('#search').val();

        if (search != "") {
            $.ajax({
                type: 'POST',
                url: 'http://localhost:81/searchUser?',
                data: `user=${search}`,
                headers: {
                    "Access-Control-Allow-Origin": "*"
                },
                success: function (data) {
                    let results = JSON.parse(data)

                    if (results.res.length == 0) {
                        $('#result').html('<br><h5 style="text-align:center">Aucun utilisateur ne correspond à votre recherche</h5>');
                    }

                    for (element of results.res) {

                        let html = `
                    <tr>
                    <td>${element.lastname}</td>
                    <td>${element.firstname}</td>
                    <td>${element.email}</td>
                    <td>${element.status == 1 || element.status == 2 ? 'actif' : 'inactif'}</td>
                    <td>${element.created_at.substr(0, 10)}</td>

                    <td>

                        <form action="/editUserRole" method="POST">
                            <select id="role" name="role">
                                <option value="${element.role}">${element.role}</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                                <option value="public">Public</option>
                            </select>
                    </td>
                    <td>
                        <div class="updatebutton">
                            <button type="submit" onclick="confirm('Confirmer la modification du rôle ?')">Update</button>
                        </div>
                        <input type="hidden" name="id" value="${element.id}">

                        </form>
                    </td>
                    <td>
                        <div class="supprimebutton">
                            <a href="/deleteUser?id=${element.id}" onclick="confirm('Confirmer la suppression ?')">Supprimer</a>
                        </div>
                    </td>
                </tr>`
                        $('#result').append(html)
                    }
                }
            });
        }
    });
});