<?php ob_start(); ?>

<style>
    .unliked {
        color: grey;
    }

    .liked {
        color: red;
    }
</style>

<?php if (isset($_SESSION['admin'])) : ?>
    <a href="/gerer-mes-articles">Back</a>
<?php else : ?>
    <a href="/articles">Back</a>
<?php endif ?>


<section class="mainuser">
    <div class="container">
        <div class="block-article" style="min-height:250px;width:70%;margin:0 auto">
            <h1><?= $article->getTitle() ?></h1>
            <small class="small">Posté par Admin - Catégorie : <?= $category->getName() ?> -
                <span id="heart_like" style="cursor:pointer;font-size:20px" data-article="<?= $article->getId() ?>">♥</span>
                <span id="count_like"><?= $total_likes ?></span> -
            </small>
            <small class="small">commentaires(<?= $countComments['count'] ?>)</small>
            <p><?= $article->getContent() ?></p>
        </div>

        </section>


        <div class="block-comment" style="margin:100px auto 15px auto; width:500px">
            <button type=" button" id="postComment">Laisser un commentaire</button><br>
            <form id="formComment" style="width:450px;margin-top:80px;display:none" data-article="<?= $article->getId() ?>">
                <span id="alertComment" style="height:50px"></span>
                <input style="display:block;width:450px" id="titleComment" placeholder="pseudo"/><br>
                <textarea style="display:block;width:450px;height:160px" id="contentComment" placeholder="Mon commentaire..."></textarea><br>
                <button class="buttonComment" style="display:block;width:100px;margin:0 auto" type="button" id="send">Poster</button>
            </form>
        </div>



        <div class=" comments mainuserComment" style="width:50%;margin:0 auto">
            <?php if (isset($comments)) : ?>
                <?php foreach ($comments as $comment) : ?>
                    <hr>
                    <div style="display:flex; justify-content:space-between">
                        <small class="small space__comment" style=font-weight:bold;font-style:italic>Commenté par <?= $comment['firstname'] . ' ' . $comment['lastname'] ?> le
                            <?= substr($comment['created_at'], 0, 10) ?> à <?= substr($comment['created_at'], -9, 18) ?>
                        </small>
                        <a title="signaler" href="/reportComment?id=<?= $comment['idComment'] ?>">
                            <img src="/public/assets/image/warning.svg" alt="" width="19" height="19">
                        </a>
                    </div>
                    <div style="display:flex;align-items:center;margin-top:10px;">
                        <span style="padding:10px;border-radius:50%;background:grey;color:white">
                            <?= ucfirst(substr($comment['firstname'], 0, 1)) . ucfirst(substr($comment['lastname'], 0, 1)) ?>
                        </span>
                        &nbsp;&nbsp;
                        <h3 style="margin-top:15px"><?= $comment['title'] ?></h3>
                        &nbsp;&nbsp;
                        <!-- button reply comment -->
                        <!-- remplacer 1 par la variable de session user -->
                        <button type="button" class="reply" data-replyer="1" data-parentid="<?= $comment['idComment'] ?>" data-article="<?= $article->getId() ?>">
                            reply
                        </button>
                    </div>
                    <span class="contentCommentSpan">
                        <?= $comment['content'] ?>
                    </span>

                    <br>
                    

                    <?php if (isset($replies)) : ?>
                        <div class="replyComments-block" id="replyComment<?= $comment['idComment'] ?>">
                            <?php foreach ($replies as $reply) : ?>
                                <?php if ($reply['parent'] == $comment['idComment']) : ?>
                                    <br>
                                    <br>
                                    <div style="margin-left:50px;padding:8px 0; border-top:1px solid black">
                                        <div style="display:flex; justify-content:space-between">
                                            <small class="small" style="font-weight:bold;font-style:italic">répondu par <?= $reply['firstname'] . ' ' . $reply['lastname'] ?> le
                                                <?= substr($reply['created_at'], 0, 10) ?> à <?= substr($reply['created_at'], -9, 18) ?>
                                            </small>
                                            <a title="signaler" href="/reportComment?id=<?= $reply['idComment'] ?>">
                                                <img src="/public/assets/image/warning.svg" alt="" width="19" height="19">
                                            </a>
                                        </div>
                                        <div style="display:flex;align-items:center;margin-top:10px;">
                                            <span style="padding:5px;border-radius:50%;background:grey;color:white">
                                                <?= ucfirst(substr($reply['firstname'], 0, 1)) . ucfirst(substr($reply['lastname'], 0, 1)) ?>
                                            </span>
                                            &nbsp;
                                            <small class="contentCommentSpan">
                                                <?= $reply['content'] ?>
                                            </small>
                                            &nbsp;
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript" src="/public/src/js/comment.js"></script>
<script type="text/javascript" src="/public/src/js/like.js"></script>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>