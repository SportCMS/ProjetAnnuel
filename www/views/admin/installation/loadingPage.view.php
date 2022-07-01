<?php ob_start(); ?>
<div class="c">
    <div></div>
    <div></div>
    <div></div>
</div>
<br>
<h1 style="margin-top:15px">Veuillez patienter pendant l'installation</h1>
<br>
<small style="text-align:center;display:block" id="msgLoading"></small>
<br>


<p id="timer">
</p>
<style>
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: lightgrey;
    }

    .main {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-evenly;
        width: 100%
    }


    .c {
        display: inline-block;
        position: relative;
        left: 40%;
        width: 160px;
        height: 60px;
    }

    .c div {
        display: inline-block;
        position: absolute;
        left: 8px;
        width: 16px;
        background: #fff;
        animation: c 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
    }

    .c div:nth-child(1) {
        left: 8px;
        animation-delay: -0.24s;
    }

    .c div:nth-child(2) {
        left: 32px;
        animation-delay: -0.12s;
    }

    .c div:nth-child(3) {
        left: 56px;
        animation-delay: 0;
    }

    @keyframes c {
        0% {
            top: 8px;
            height: 64px;
        }

        50%,
        100% {
            top: 24px;
            height: 32px;
        }
    }
</style>
<script type=" text/javascript" src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    window.onload = () => {
        countDown()
    }
    var count = 12;

    function countDown() {
        var timer = document.querySelector("#timer");
        if (count > 0) {
            if (count >= 10) {
                $('#msgLoading').html('Installation de la base de donnée <?= $_SESSION['temp_dbName'] ?>....')
                $('#msgLoading').fadeIn()
            }
            if (count < 10 && count >= 7) {
                $('#msgLoading').html('Création d\'un jeu de donnée.....')
                $('#msgLoading').fadeIn()
            }
            if (count < 7 && count >= 3) {
                $('#msgLoading').html('Installation du site.....')
                $('#msgLoading').fadeIn()
            }
            if (count < 3 && count >= 1) {
                $('#msgLoading').html('Création des droits administrateur.....')
                $('#msgLoading').fadeIn()
            }
            count--;
            setTimeout("countDown()", 1000);
        } else {
            window.location.href = "/dashboard";
        }
    }
</script>


<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/baseSecurity.php'); ?>