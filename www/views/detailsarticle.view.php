<h1>details article</h1>
<!-- ajout -->
<a href="/articles">Back</a>

<h1><?= $article->getTitle() ?></h1>
<small>Posté par Admin - Catégorie : <?= $category->getName() ?> - <a href="#">Likes(0)</a></small>
<p><?= $article->getContent() ?></p>
<button type="button" id="postComment">Laisser un commentaire</button>
<form id="formComment" style="width:450px;margin-top:80px;display:none">
    <span id="errorComment" style="color:red;height:50px"></span>
    <input style="display:block;width:450px" id="titleComment" /><br>
    <textarea style="display:block;width:450px;height:160px" id="contentComment"></textarea><br>
    <button style="display:block;width:100px;margin:0 auto" type="button" id="send">Poster</button>
</form>


<!-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="/public/assets/js/comment.js"></script> -->