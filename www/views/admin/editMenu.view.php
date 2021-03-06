<?php ob_start(); ?>
<div style="display:flex; align-items:center">
    <h1>Gérer mon menu</h1><br><br>
</div>

<div class="right-render-menu-items" style="min-height:80">
    <div style="padding:20px;margin:100px;border:1px solid grey;min-height:60vh;background:white">
        <ul class="site-menu-nav-list droppable connected-sortable">
            <h3>Mon menu de navigation</h3>
            <p>Drag and dropper les items pour organiser le menu</p>
            <?php foreach ($items as $item) : ?>
                <li style="cursor:pointer;border:0.5px solid grey;list-style:none;padding:25px 10px;background:#D6DCE1;width:95%;" class="draggable-item" id="<?= $item['id'] ?>">
                    <?= $item['name'] ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>

    <div class="form_container">
        <h1>Ajouter un lien NAV</h1>
        <form class="form_builder">
            <div class="logo__input">
            <span>Donnez un nom à votre nouveau lien de navigation</span><br>
            <input class="form_input" type="text" id="item-name" /><br><br>
            </div>
            <div class="logo__input">
            <span>Choisir une destination (créer une page pour ajouter une nouvelle destination)</span><br>
            <select id="item-route">
                <option value="">Choisir une page</option>
                <?php foreach ($pages as $page) : ?>
                    <option value="<?= $page['title'] ?>"><?= $page['title'] ?></option>
                <?php endforeach ?>
            </select><br><br>
            </div>
            <button id="addItem">valider</button>
            <br><small id="error" style="color:red;font-size:11px"></small>
        </form>
    </div>
</div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>



<script type=" text/javascript" src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.13.0/jquery-ui.js" integrity="sha256-xH4q8N0pEzrZMaRmd7gQVcTZiFei+HfRTBPJ1OGXC0k=" crossorigin="anonymous"></script>

<script>
    $(".droppable").sortable({ // drag and drop
        connectWith: ".connected-sortable", 
        stack: '.connected-sortable li',
        stop: function(e, ui) { // when drag and drop stop
            let list = $.map($(this).find('li'), function(el) { // get all li
                return [{
                    'id_block': el.id, // get id of li
                    'position': $(el).index() - 1 // get position of li
                }]

                var ids = $('.droppable li').map(function(i) { 
                    return this.id; // get id of li
                }).get();
            });

            console.log(list)

            let chain = ""; 
            for (item of list) {
                chain += `&${item.id_block}=${item.position}`
            }

            $.ajax({
                type: "POST",
                url: 'http://localhost:81/moveItemPosition?',
                headers: {
                    "Access-Control-Allow-Origin": "*"
                },
                data: chain,
                success: function(rep) {
                    console.log(rep)
                }
            });
        }
    });

    $('#addItem').on('click', () => {
        let name = $('#item-name').val()
        let route = $('#item-route').val()

        if (route == '') {
            $('#error').html('veuillez rentrer une route valide')
            return;
        }

        if (!name || name == null || name.length == 0) {
            $('#error').html('veuillez rentrer un nom valide')
            return;
        }
        data = `name=${name}&route=${route}`

        $.ajax({
            type: "POST",
            url: 'http://localhost:81/addItem?',
            headers: {
                "Access-Control-Allow-Origin": "*"
            },
            data: data,
            success: function(rep) {
                let response = JSON.parse(rep)
                $('.connected-sortable').append(
                    `<li style="border:0.5px solid grey;list-style:none;padding:25px 10px;background:#D6DCE1;width:95%;cursor:pointer" id="${response.id}"><span contenteditable="true" id="text-item">${name}</span></li>`
                )
            }
        });

    });
</script>