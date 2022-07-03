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