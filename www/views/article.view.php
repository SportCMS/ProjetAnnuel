

    <h1 style="padding-top:300px;">Poster un article</h1>
    
    <!-- ajout argument -->
    <?php $this->includePartial("form", $article->getArticleForm(null)); ?>
    
    <!-- envoie des erreurs -->
    <?php if (isset($result)) : ?>
        <?php foreach ($result as $key => $value) : ?>
            <p><?= $value ?></p>
            <?php endforeach; ?>
            <?php endif; ?>
            

            
            <!-- ajout wysywig -->
            <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
            <script>
                ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });
            </script>


