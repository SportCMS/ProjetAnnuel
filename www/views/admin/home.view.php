<?php

use App\Helpers\DateHelper;

ob_start(); ?>

<style>
    .card-white {
        margin-top: 10px;
        width: 300px;
        min-height: 15vh;
        background: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .card-grey {
        margin-top: 10px;
        width: 300px;
        min-height: 15vh;
        background: lightgrey;
        padding: 20px;
        border-radius: 8px;
        text-align: center;

    }
</style>

<div class="containerDshb">
    <div style="display:flex;justify-content:space-between">
        <div>
            <!--1er chart-->
            <h2 class="titleCmpn">Fréquentation du site : <?= DateHelper::dateConverter('monthDate') ?></h2>
            <div class="card-grey">
                <canvas id="chartU"></canvas>
            </div>
        </div>
        <div>
                    <!--1er chart-->
                    <h2 class="titleCmpn">Nombre d'utilisateurs inscrits</h2>
            <div class="card-white">
                <p>Total : <?= $userStat ?></p><br>
                <p>Ce mois-ci : <?= isset($monthUsers['count'])?$monthUsers['count']:0 ?></p><br>
                <p>Cette semaine: <?= $countWeekUsers['count'] ?></p><br>
                <?= var_dump($countWeekUsers['count']) ?>
                <p>Aujourd'hui: <?= isset($todayUsers['count'])?$todayUsers['count']:0 ?></p>
            </div>
        </div>
        <div>
            <!--2nd chart-->
            <h2 class=" titleCmpn">Inscriptions : <?= DateHelper::dateConverter('monthDate') ?></h2>
            <div class="card-grey">
                <canvas id="chartL"></canvas>
            </div>
        </div>
    
    </div>
    <div class="containerDshb" style="margin-top:40px;display:flex;justify-content:space-between">
    <!--2eme partie dashboard msg, agenda, dernier iscrit-->
    <div class="card-white" style="width:45%">
        <h2 class="titleCmpn">Derniers inscrits</h2><br>
        <div>
            <?php foreach ($lastUsers as $user) :  ?>
                <span style="padding: 8px 3px; border-bottom:1px solid lightgrey;display:block"> <?= $user['firstname'] ?> <?= $user['lastname'] ?> inscrit le <?= (new \Datetime($user['created_at']))->format('d/m/Y') ?></span><br>
            <?php endforeach ?>
        </div>
    </div>
    <div class=" card-grey" style="width:45%">
        <h2 class="titleCmpn">Derniers messages</h2><br>
        <div>
            <?php if (count($lastsContacts) > 0) : ?>
                <?php foreach ($lastsContacts as $lastContact) :  ?>
                    <div style="padding: 8px 3px; border-bottom:1px solid black;">
                        <small><?= $lastContact['message'] ?></small><br>
                        <small style="font-style:italic;font-weight:bold">Envoyé par <?= $lastContact['email'] ?>, le <?= (new \Datetime($lastContact['created_at']))->format('d/m/Y') ?></small><br>
                    </div>
                <?php endforeach ?>
            <?php else : ?>
                <small>Vous n'avez pas encore reçu de message</small>
            <?php endif ?>

        </div>
    </div>
</div>
</div>
<script type="text/javascript">
        let month = `/<?= date('m') ?>`;
    var ctxU = document.getElementById('chartU').getContext('2d');
    var gradient = ctxU.createLinearGradient(0, 0, 0, 450);
    gradient.addColorStop(0, 'rgba(255, 255,255, 0.5)');
    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
    var myChart = new Chart(ctxU, {
        type: 'line',
        label: "Periodes du mois en cours",
        data: {
            labels: [
                <?php foreach ($connexionData as $data => $value) : ?>
                    <?= $data  ?> + month,
                <?php endforeach ?>

            ],
            datasets: [{
                label: "Traffic en nombre d'utilisateurs",
                data: [
                    <?php foreach ($connexionData as $data => $value) : ?>
                        <?= $value['count'] ?>,
                    <?php endforeach ?>
                ],
                lineTension: 0,
                backgroundColor: gradient,
                pointBackgroundColor: '#FFF',
                borderWidth: 2,
                borderColor: '#FFF'
            }]
        },

        options: {
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: "#FFF",
                        beginAtZero: true,
                        tickLength: 20
                    },
                    gridLines: {
                        color: "#FFF",
                        drawOnChartArea: false
                    }
                }],
                yAxes: [{
                    ticks: {
                        fontColor: "#FFF",
                        beginAtZero: true
                    },
                    gridLines: {
                        color: "#FFF",
                        drawOnChartArea: false
                    }
                }]
            },
            tooltips: {
                titleFontColor: '#6FA7FF',
            }
        }
    });

    var ctxL = document.getElementById('chartL').getContext('2d');
    var gradient = ctxL.createLinearGradient(0, 0, 0, 450);
    gradient.addColorStop(0, 'rgba(255, 255,255, 0.5)');
    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
    var myChart = new Chart(ctxL, {
        type: 'line',
        label: "periodes du mois en cours",
        data: {
            labels: [
                <?php foreach ($inscriptionData as $data => $value) : ?>
                    <?= $data  ?> + month,
                <?php endforeach ?>
            ], //Axes abscisses
            datasets: [{
                label: "Inscriptions en nbre",
                data: [
                    <?php foreach ($inscriptionData as $data => $value) : ?>
                        <?= $value['count'] ?>,
                    <?php endforeach ?>
                ], //Axes abscisses
                lineTension: 0,
                backgroundColor: gradient,
                pointBackgroundColor: '#FFF',
                borderWidth: 2,
                borderColor: '#FFF'
            }]
        },
        options: {
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: "#FFF",
                        beginAtZero: true,
                        tickLength: 10
                    },
                    gridLines: {
                        color: "#FFF",
                        drawOnChartArea: false
                    }
                }],
                yAxes: [{
                    ticks: {
                        fontColor: "#FFF",
                        beginAtZero: true
                    },
                    gridLines: {
                        color: "#FFF",
                        drawOnChartArea: false
                    }
                }]
            },
            tooltips: {
                titleFontColor: '#6FA7FF',
            }
        }
    });

</script>

<div>


<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>