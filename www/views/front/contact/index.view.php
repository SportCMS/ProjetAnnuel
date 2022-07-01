<?php ob_start(); ?>

<h1>Laissez nous un message</h1>

<form action="" method="post">
    <div class="ligne">
        <p class="encare">
            <label>Votre message ici</label>
        </p>
        <textarea type="text" name="message" id="lock"></textarea>
    </div>
    <button class="button button--form">valider</button>
    <small id="msg_alert" style="color:<?= isset($alert) && $alert[0] == 'success' ? 'green' : 'red' ?>">
        <?= isset($alert) ? $alert[1] : '' ?>
    </small>
</form>

<style>
    @import url(https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,700;1,400;1,700&display=swap);
    @import url(https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap);
    @import url(https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

    body {
        background-color: #F7FAFC;
        font-family: "Roboto", sans-serif;
        font-style: normal;
        font-weight: 400;
    }

    main {
        padding-top: 6%;
        text-align: center;
        margin: auto;
    }

    main .title {
        text-align: center;
        margin-bottom: 1em;
        cursor: pointer;
    }

    main form {
        font-size: 20px;
        margin: auto;
        width: 500px;
        padding: 0.75em 2.75em 0.25em 2.75em;
        background-color: #FFFFFF;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        color: #2A4365;
    }

    main form textarea {
        position: relative;
        width: 100%;
        height: 180px;
        background-color: #EDF2F7;
        border-radius: 5px;
        border: none;
        font-size: 24px;
        resize: none;
    }

    main form .ligne {
        margin-top: 1em;
    }

    main form .ligne .encare {
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    main form .ligne .encare label {
        margin-left: 0.5em;
    }

    main form .link {
        text-decoration: none;
    }

    main form button {
        font-size: 20px;
        margin: auto;
        width: 35%;
        margin-top: 2.5em;
        margin-bottom: 1em;
        background: #2A4365;
        color: white;
        height: 60px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }

    @media screen and (max-width: 640px) {
        form {
            font-size: 14px;
            margin: auto;
            width: 260px !important;
        }

        form input {
            height: 35px;
            font-size: 20px;
        }

        form button {
            font-size: 8px;
            height: 50px;
        }
    }
</style>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>