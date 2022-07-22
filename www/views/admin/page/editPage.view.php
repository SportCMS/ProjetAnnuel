<?php ob_start(); ?>

<div>
    <h1>Editer la page <?= $_GET['page'] ?> <small style="font-size:11px"><a href="/<?= $_GET['page'] ?>">Visualiser</a></small></h1><br>
    <p>Vous pouvez ajouter des blocs sur votre page pour créer du contenu.</p>
    <p>Organisez vos blocs en drag and droppant</p>
    <br>
</div>
<br>
<div class="display-page droppable connected-sortable">
    <form action="" class="createBlock" method="POST">
        <button type="submit" name="createBlock"><span style="font-size:32px" title="Ajouter un block">
                <ion-icon name="add-outline"></ion-icon>
                <small style="font-size:12px;display:block">Ajouter un block</small>
            </span>
        </button>
    </form><br>

    <?php foreach ($blocksPage as $block) : ?>
        <div style="padding:40px;border:1px solid grey; position:relative;z-index:1;" data-idblock="<?= $block['blockId'] ?>">
            <?php if (isset($block['content'])) : ?>
                <?= $block['content'] ?>
            <?php elseif (isset($block['formTitle'])) : ?>
                <?= 'formulaire : ' . $block['formTitle'] ?>
            <?php else : ?>
                <?= $block['title'] ?>
            <?php endif ?>

            <?php if ($block['title'] == 'untitled') :  ?>
                <span style="position:absolute;top:5px;right:5px;cursor:pointer" class="menuBlock" data-page="<?= $_GET['page'] ?>" data-block="<?= $block['blockId'] ?>">
                    <ion-icon name="ellipsis-vertical-outline"></ion-icon>
                </span>
            <?php endif ?>
            <div style="width:200px;border:1px solid black;background:lightgrey;position:absolute;top:10px;right:25px;z-index:10; visibility:hidden">
                <small class="item-menu-block">Texte</small>
                <small class="item-menu-block">Form</small>
                <small class="item-menu-block">
                    <a href="/removeBlock?id=<?= $block['blockId'] ?>&page=<?= $_GET['page'] ?>">delete</a>
                </small>
            </div>
            <div class="form-div" id="form-div" style="margin-top:20px">
                <form class="text-form" id="text-form" style="display:none">
                    <div class="ligne">
                        <p class="encare">
                            <label>Entrer votre texte</label>
                        </p>
                        <textarea id="input-text-form" style="resize:none;width:90%;height:150px"></textarea>
                    </div>
                    <button type="button" class="submit-text-form button button--form">Valider</button>
                </form>
                <form class="card-form" id="card-form" style="display:none">

                    <button type="button" class="submit-card-form button button--form">valider</button>
                </form>
                <form class="builder-form" id="builder-form" style="display:none">
                    <div class="ligne">

                        <p class="encare" style="margin-top:10px">
                            <label>Ajouter un champs de formulaire</label>
                            <span id="newInput" style="cursor:pointer;font-size:14px;padding:8px;border-radius:50%;border:1px solid lightgrey;">
                                <ion-icon name="add-outline"></ion-icon>
                            </span>
                        </p>

                        <select id="input-card-form" style="padding:6px 30px">
                            <option value="">Choisir un type de formulaire</option>
                            <option value="contact">Contact (1 champs email + 1 champ message)</option>
                            <option value="newsletter">Newsletter (1 champs email)</option>
                        </select>

                        <!-- <input type="text" id="input-card-form" style="padding:6px 40px" placeholder="nom du formulaire" /> -->
                    </div>

                    <div class="ligne input-lines" style="visibility:hidden;display:flex">
                        <p class="encare" style="margin-top:10px">
                            <select id="input-type" style="padding:6px 30px">
                                <option value="">Choisir</option>
                                <option value="text">text</option>
                                <option value="number">number</option>
                                <option value="password">password</option>
                                <option value="email">email</option>
                                <option value="textarea">textarea</option>
                                <option value="submit">submit</option>
                            </select>*
                            <input type="text" id="input-id" style="padding:6px 30px" placeholder="Identifier le champs">
                            <input type="text" id="input-label" style="padding:6px 30px" placeholder="label">
                            <input type="text" id="input-choice" style="padding:6px 30px" placeholder="placeholder">
                            <span style="cursor:pointer;font-size:20px;vertical-align:middle">
                                <ion-icon name="save-outline"></ion-icon>
                            </span>
                        </p>
                    </div>
                </form>
            </div>

        </div>
    <?php endforeach ?>
    <span id="alert-blocks"></span>

</div>

<style>
    .display-page {
        width: 75%;
        padding: 20px;
        border: 1px solid grey;
        background: white;
        margin: 15px auto;
        min-height: 100vh;
        text-align: center;
    }

    .createBlock button {
        width: 80%;
        margin: 10px auto;
        background: none;
        padding: 10px;
        cursor: pointer;
    }

    .item-menu-block {
        cursor: pointer;
        width: 100%;
        padding: 8px 5px;
    }

    .item-menu-block:hover {
        color: purple;
    }
</style>
<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>

<script type=" text/javascript" src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.13.0/jquery-ui.js" integrity="sha256-xH4q8N0pEzrZMaRmd7gQVcTZiFei+HfRTBPJ1OGXC0k=" crossorigin="anonymous"></script>

<script>
    let menuBlock = document.querySelectorAll('.menuBlock');
    menuBlock.forEach(function(menu) { 
        menu.addEventListener('click', () => { 
            menu.nextElementSibling.style.visibility = 'visible'; // afficher le menu

            let items = menu.nextElementSibling.children // récupérer les items du menu
            for (let i = 0; i < items.length; i++) { // ajouter les evenements aux items du menu
                items[i].addEventListener('click', () => {

                    if (items[i].textContent == 'Texte') { 
                        menu.nextElementSibling.style.visibility = 'hidden'; // on cache le menu
                        let formChildrens = menu.nextElementSibling.nextElementSibling.children //form-div
                        formChildrens[0].style.display = 'block' //text-form
                        formChildrens[1].style.display = 'none' //form
                        formChildrens[1].style.display = 'none' //card

                        formChildrens[0].lastElementChild.addEventListener('click', () => { //submit-text-form

                            if (formChildrens[0].lastElementChild.previousElementSibling.lastElementChild.value.length == 0) {
                                alert('Veuillez entrer un contenu')
                                return;
                            }
                            let content = formChildrens[0].lastElementChild.previousElementSibling.lastElementChild.value; // on récupère le contenu du textarea
                            let block = menu.dataset.block // on récupère l'id du block

                            $.ajax({
                                type: "POST", 
                                url: 'http://localhost:81/createTextBlock?', 
                                headers: {
                                    "Access-Control-Allow-Origin": "*",
                                },
                                data: `content=${content}&block=${block}`,
                                success: function(rep) { 
                                    let response = JSON.parse(rep) 
                                    console.log(response) 
                                    formChildrens[0].style.display = 'none' 
                                    menu.parentElement.innerHTML = response.content 
                                }
                            });
                        })

                    }
                    if (items[i].textContent == 'Form') { 
                        menu.nextElementSibling.style.visibility = 'hidden'; // on cache le menu
                        let formChildrens = menu.nextElementSibling.nextElementSibling.children //form-div
                        formChildrens[0].style.display = 'none' //text-form
                        formChildrens[1].style.display = 'none' //card 
                        formChildrens[2].style.display = 'block' //form

                        let form = menu.nextElementSibling.nextElementSibling.children[2]; // on récupère le formulaire
                        let newInput = form.firstElementChild.children[0].lastElementChild // on récupère le champ input

                        newInput.addEventListener('click', () => { 
                            let newLine = form.children[1] // on récupère la ligne du formulaire
                            let copy = newLine.cloneNode(true) // on copie la ligne du formulaire
                            copy.style.visibility = 'visible' // on affiche la ligne copiée
                            copy.classList.add('cool') // on ajoute la classe cool pour la stylisation
                            form.appendChild(copy) // on ajoute la ligne copiée au formulaire

                            document.querySelectorAll('.input-lines').forEach(function(inputLine) { // on ajoute les évènements aux nouveaux champs input

                                let button = inputLine.children[0].lastElementChild // on récupère le bouton de suppression
                                let block = menu.dataset.block // on récupère id du block

                                button.addEventListener('click', () => { 

                                    let chain = '';
                                    let select = inputLine.children[0].children[0] // on récupère le select
                                    let choice = select.selectedIndex; // on récupère la valeur du select
                                    let type = select.options[choice].value // on récupère le type du select

                                    let formSelect = form.firstElementChild.lastElementChild // on récupère le select du formulaire

                                    let formSelectChoice = formSelect.selectedIndex; // on récupère la valeur du select du formulaire
                                    let formSelectChoiceValue = formSelect.options[formSelectChoice].value // on récupère le type du select du formulaire

                                    let name = inputLine.children[0].children[1].value // on récupère le nom du champ input
                                    let label = inputLine.children[0].children[2].value // on récupère le label du champ input
                                    let placeholder = inputLine.children[0].children[3].value // on récupère le placeholder du champ input

                                    chain = `block=${block}&form=${formSelectChoiceValue}&type=${type}&name=${name}&placeholder=${placeholder}&label=${label}`;

                                    $.ajax({
                                        type: "POST",
                                        url: 'http://localhost:81/createFormInput?',
                                        headers: {
                                            "Access-Control-Allow-Origin": "*",
                                        },
                                        data: chain,
                                        success: function(rep) {
                                            let response = JSON.parse(rep)
                                            console.log(response)

                                            if (response.status == 'success') {
                                                document.getElementById('alert-blocks').innerHTML = ""
                                                document.getElementById('alert-blocks').innerHTML = response.message
                                                document.getElementById('alert-blocks').style.color = 'green'
                                                inputLine.children[0].children[4].innerHTML = ""
                                                inputLine.children[0].children[4].innerHTML = `<ion-icon name="checkmark-outline"></ion-icon>`

                                            }
                                            if (response.status == 'error') {
                                                document.getElementById('alert-blocks').innerHTML = ""
                                                document.getElementById('alert-blocks').innerHTML = response.message
                                                document.getElementById('alert-blocks').style.color = 'red'
                                            }
                                        }
                                    });
                                })
                            })

                        })
                    }
                });
            }
        })
    }); 

    $(".droppable").sortable({ // pour le drag and drop
        connectWith: ".connected-sortable", 
        stack: '.connected-sortable div',
        stop: function(e, ui) { 
            let list = $.map($(this).find('div'), function(el) { 
                return [{
                    'id_block': el.dataset.idblock, 
                    'position': $(el).index() - 1 
                }]

                var ids = $('.droppable div').map(function(i) {
                    return this.id;
                }).get();
            });

            console.log(list)

            let chain = "";
            for (item of list) {
                chain += `&${item.id_block}=${item.position}`
            }

            $.ajax({
                type: "POST",
                url: 'http://localhost:81/saveItemPosition?',
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
</script>