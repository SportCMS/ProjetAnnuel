<?php ob_start(); ?>
<section class="stats">
    <!--Titre dashboard-->
    <h1>Statistiques</h1>
    <div class="date">
        <h3>Du</h3>
        <div>10/01/2022</div>
        <h3>au</h3>
        <div>16/01/2022</div>
    </div>
</section>
<div class="containerDshb">
    <!--Premier partie graph dashboard-->
    <div>
        <!--1er chart-->
        <h2 class="titleCmpn">Nombre d’utilisateurs uniques</h2>
        <div class="graph">
            <canvas id="chartU"></canvas>
        </div>
    </div>
    <div>
        <!--2nd chart-->
        <h2 class="titleCmpn">Nombre de nouveaux licenciés</h2>
        <div class="graph">
            <canvas id="chartL"></canvas>
        </div>
    </div>
</div>
<div class="containerDshb">
    <!--2eme partie dashboard msg, agenda, dernier iscrit-->
    <div>
        <h2 class="titleCmpn">Calendrier</h2>
        <!--Agenda-->
        <div class="diary">
            <!--Agenda coté back-->
            <div class="month-year">Janvier 2022</div>
            <!--Premiere partie agenda-->
            <div class="days">
                <!--Nom jours semaines-->
                <div>LUN</div>
                <div>MAR</div>
                <div>MER</div>
                <div>JEU</div>
                <div>VEN</div>
                <div>SAM</div>
                <div>DIM</div>
            </div>
            <div class="days-week">
                <!--Boites du plannings avec ses étiquettes-->
                <div>
                    <a href="#">10</a>
                    <div class="container-label">
                        <div class="label label--green">
                            <div></div>15h45/16h45 : U-16
                        </div>
                        <div class="label">
                            <div></div>17h00/18h30 : U-18
                        </div>
                        <div class="label label--blue">
                            <div></div>18h45/20h15 : U-20
                        </div>
                        <div class="label label--purple">
                            <div></div>20h30/21h30 : U-40
                        </div>
                        <div class="label label--yellow">
                            <div></div>21h45/23h00 : Libre
                        </div>
                    </div>
                </div>
                <div>
                    <a href="#">11</a>
                    <div class="container-label">
                        <div class="label label--yellow">
                            <div></div>17h00/20h15 : Libre
                        </div>
                    </div>
                </div>
                <div>
                    <a href="#">12</a>
                    <div class="container-label">
                        <div class="label label--green">
                            <div></div>19h00/20h30 : U-16
                        </div>
                    </div>
                </div>
                <div>
                    <a href="#">13</a>
                    <div class="container-label">
                        <div class="label">
                            <div></div>19h00/20h30 : U-18
                        </div>
                    </div>
                </div>
                <div>
                    <a href="#">14</a>
                    <div class="container-label">
                        <div class="label label--purple">
                            <div></div>19h00/20h30 : U-40
                        </div>
                    </div>
                </div>
                <div>
                    <a href="#">15</a>
                    <div class="container-label">
                        <div class="label label--orange">
                            <div></div>19h00/20h30 : U-10
                        </div>
                    </div>
                </div>
                <div>
                    <a href="#">16</a>
                    <div class="container-label">

                    </div>
                </div>
            </div>
        </div>
        <!--Fin agenda-->
        <h2 class="titleCmpn">Derniers enregistrements</h2>
        <div class="last-register">
            <div class="title">
                <div class="firstname">Nom</div>
                <div class="lastname">Prénom</div>
                <div class="mail">Email</div>
                <div class="actif">Compte actif</div>
            </div>
            <div class="scroll">
                <div class="box box--grey">
                    <div class="firstname">Morade</div>
                    <div class="lastname">CHEMLAL</div>
                    <div class="mail">morade.chemlal@gmail.com</div>
                    <div class="actif">Non</div>
                </div>
                <div class="box">
                    <div class="firstname">Antoine</div>
                    <div class="lastname">CHABERNAUD</div>
                    <div class="mail">antoine.chabe@gmail.com</div>
                    <div class="actif">Oui</div>
                </div>
                <div class="box box--grey">
                    <div class="firstname">Ayman</div>
                    <div class="lastname">BEDDA</div>
                    <div class="mail">ayman.bedda@gmail.com</div>
                    <div class="actif">Non</div>
                </div>
                <div class="box">
                    <div class="firstname">Samy</div>
                    <div class="lastname">AMALLAH</div>
                    <div class="mail">samy.amallah@gmail.com</div>
                    <div class="actif">Non</div>
                </div>
                <div class="box box--grey">
                    <div class="firstname">Ayman</div>
                    <div class="lastname">BEDDA</div>
                    <div class="mail">ayman.bedda@gmail.com</div>
                    <div class="actif">Non</div>
                </div>
                <div class="box">
                    <div class="firstname">Samy</div>
                    <div class="lastname">AMALLAH</div>
                    <div class="mail">samy.amallah@gmail.com</div>
                    <div class="actif">Non</div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <!--Message-->
        <h2 class="titleCmpn">Messages</h2>
        <div class="msg">
            <div class="title">
                <span class="user-info">Prénom</span>
                <span class="user-msg">Dernier message</span>
            </div>
            <div class="box box--grey">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile.svg" alt="">
                    </div>
                    Simon
                </span>
                <span class="user-msg unread">
                    Lorem ipsum dolor sit amet.
                    <div class="btn-unread"></div>
                </span>
            </div>
            <div class="box">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile1.jpg" class="hasImg" alt="">
                    </div>
                    Antoine
                </span>
                <span class="user-msg unread">
                    Lorem ipsum dolor sit amet.
                    <div class="btn-unread"></div>
                </span>
            </div>
            <div class="box box--grey">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile2.jpg" class="hasImg" alt="">
                    </div>
                    Morade
                </span>
                <span class="user-msg">Lorem ipsum dolor sit amet.</span>
            </div>
            <div class="box">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile.svg" alt="">
                    </div>
                    Ayman
                </span>
                <span class="user-msg">Lorem ipsum dolor sit amet.</span>
            </div>
            <div class="box box--grey">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile3.png" class="hasImg" alt="">
                    </div>
                    Edouard
                </span>
                <span class="user-msg">Lorem ipsum dolor sit amet.</span>
            </div>
            <div class="box">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile.svg" alt="">
                    </div>
                    Samy
                </span>
                <span class="user-msg">Lorem ipsum dolor sit amet.</span>
            </div>
            <div class="box box--grey">
                <span class="user-info">
                    <div class="profile-pic">
                        <img src="assets/images/profile4.jpg" class="hasImg" alt="">
                    </div>
                    Yves
                </span>
                <span class="user-msg">Lorem ipsum dolor sit amet.</span>
            </div>
            <div class="box"></div>
            <div class="box box--grey"></div>
            <div class="box"></div>
        </div>
    </div>
</div>
<div class="containerDshb">
    <!--3eme partie chart + info diverses-->
    <div>
        <!--Container chart présent-->
        <h2 class="titleCmpn">Taux de présence semaine</h2>
        <div class="graph">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <div>
        <!--Container info div-->
        <h2 class="titleCmpn">Informations diverses</h2>
        <section class="various-info">
            <div>
                <div class="img-container"><img src="assets/images/templates.svg" alt=""></div>
                <span>Nombre de templates créer : 16</span>
            </div>
            <div>
                <div class="img-container"><img src="assets/images/page-visited.svg" alt=""></div>
                <span>Nombre de pages visitées aujourd’hui : 969</span>
            </div>
            <div>
                <div class="img-container"><img src="assets/images/present.svg" alt=""></div>
                <span>Nombre de présent aujourd’hui : 38</span>
            </div>
            <div>
                <div class="img-container"><img src="assets/images/letter.svg" alt=""></div>
                <span>Nouvelle demande de contact : 19</span>
            </div>
        </section>
    </div>
</div>


<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>