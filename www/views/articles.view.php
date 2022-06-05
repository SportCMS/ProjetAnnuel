<div style="display:flex; align-items:center; padding-top: 100px;">
    <h1>Nos articles(<?= count($all_article) ?>)</h1>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a style="text-decoration:none;border-radius:50%; background:grey;padding:5px 10px;color:white" href="/createArticle">+</a>
</div>

<?php if(isset($all_article)) : ?>
   
    <?php foreach ($all_article as $article => $value) : ?>
        <div style='display:flex;padding:10px 0'>
            <span style="font-weight:bold; margin:0 20px"><?= ucfirst($value['title']) ?></span>
            <span style="margin:0 20px"><?= substr($value['content'], 0, 40) . '...' ?></span>
            <span style='font-style:italic; margin:0 20px'><?= 'PostÃ© par : admin le ' . substr($value['created_at'], 0, 10) ?></span>
            <span style="margin:0 20px;padding:4px;background:lightgrey">
                <a href="/detailsArticle?slug=<?= $value['slug'] ?>" style="font-size:11px;text-decoration:none;color:blue">Lire la suite</a>
            </span>
            <span style="margin:0 20px;padding:4px;background:lightgrey">
                <a href="/updateArticle?slug=<?= $value['slug'] ?>" style="font-size:11px;text-decoration:none;color:blue">Editer</a>
            </span>
            <span style="margin:0 20px;padding:4px;background:lightgrey">
                <a href="/deleteArticle?id=<?= $value['id'] ?>" style="font-size:11px;text-decoration:none;color:blue" onclick="confirm('Confirmer la suppression ?')">Supprimer</a>
            </span>
        </div>
    <?php endforeach; ?>
  
<?php endif; ?>
</div>




