<?php

namespace App\Helpers;

use App\core\Sql;
use App\Helpers\Slugger;
use App\models\Page as PageModel;
use App\models\Theme as ThemeModel;
use App\models\Like as LikeModel;
use App\models\MenuItem as MenuItemsModel;
use App\models\Comment as CommentModel;
use App\models\Categorie;
use App\models\Article as ArticleModel;
use App\models\User;

class Fixture extends Sql
{

    /**
    * créations des models pour le thème foot
    */
    public function loadThemeTwentyFoot()
    {
        $theme = new ThemeModel();
        // on vide la table
        $theme->truncate('theme');
        $theme->setDescription("Un thème blogging dédié au foot");
        $theme->setName('Twenty foot theme');
        $theme->setImage('footTheme.png');
        $theme->save();

        $theme = new ThemeModel();
        $theme->setDescription("Un thème moderne et élégant, proposant des articles Tennis , un système d'inscription pour réagir sur des articles d'actualité et poster ses propres articles");
        $theme->setName('Twenty one tennis');
        $theme->setImage('tennis.jpg');
        $theme->save();

        $categoryManager = new Categorie();
         // on vide la table
        $categoryManager->truncate('categorie');
        // on definit les catégories et l'image associée
        $categorieNames = ['Actus' => 'actus.jpg', 'Ligue 1' => 'ligue1.png', 'Ligue 2' => 'ligue2.jpg', 'Foot feminin' => 'footf.jpg', 'Champions league' => 'champion.jpg', 'PSG' => 'foot.jpg'];
        // creation des categories
        foreach ($categorieNames as $key => $value) {
            $category = new Categorie();
            $category->setName($key);
            $category->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum');
            $category->setImage($value);
            $category->setSlug(Slugger::sluggify(strtolower($key)));
            $category->save();

            // on crée un tableau 'params' avec les infos de la categorie
            $params['route'] = Slugger::sluggify($key);
            $params['role'] = 'public' ?? null;
            $params['model'] = 'categories' ?? null;
            $params['action'] = 'categoryPage';
            
            // on ecrit la route de la categorie dans le yaml
            $this->writeRoute($params);
        }

        $articleManager = new ArticleModel();
        $articleManager->truncate('article');
        $categories = $categoryManager->getAll();
        $content = "CET ARTICLE EST UN MODÈLE D'EXEMPLE : Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.";
        $content = str_replace(',', '', $content);
        $content = str_replace('.', '', $content);
        $arrayContent =  explode(' ', $content);

        // tableaux de donnée  de sizes
        $sizehigh = ['500', '580', '540', '600', '630', '900', '730', '760', '800', '820', '840'];
        $sizeWidth = ['980', '1200', '1400', '1500', '630', '700', '730', '760', '800', '820', '840'];
        for ($i = 0; $i < count($categories); $i++) {
            for ($j = 0; $j < 2; $j++) {
                $k = $i . $j;
                // on genere des sizes aleatoires
                $rand =  $sizehigh[rand(0, count($sizehigh) - 1)];
                $rand2 =  $sizehigh[rand(0, count($sizeWidth) - 1)];
                $title = $arrayContent[rand(0, count($arrayContent) - 1)] . ' ' . $arrayContent[rand(0, count($arrayContent) - 1)] . ' ' . $arrayContent[rand(0, count($arrayContent) - 1)];
                // on crée l'article
                $article = new ArticleModel();
                $article->setCategoryId($categories[$i]['id']);
                $article->settitle($title);
                $article->setContent($content);
                // appel à l'api d'image loremflickr avec les sizes en parametres de requete
                $article->setImage("https://loremflickr.com/{$rand2}/{$rand}/soccer?random={$k}");
                // on crée un slug grace au Helper Slugger
                $article->setSlug(Slugger::sluggify($title));
                $article->save();
            }
        }

        $userManager = new User();
        $userManager->truncate('user'); 

        $articles = $articleManager->getAll();
        $users = $userManager->getAll();
        $commentManager = new CommentModel();
        $likeManager = new LikeModel();
        $commentManager->truncate('comment');
        $likeManager->truncate('like');
        $contentComment = "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatu unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab ";
        $contentComment = str_replace(',', '', $contentComment);
        $contentComment = str_replace('.', '', $contentComment);
        $explode = explode(' ', $contentComment);

        // pour chaque article
        foreach ($articles as $article) {
            $rand = rand(2, 10);
            $rand2 = rand(1, 3);

            // on crée un nombre de commentaire aleatoire
            for ($j = 0; $j < $rand; $j++) {
                $comment = new CommentModel();
                $comment->setParentId(null);
                $comment->setAuthorId($users[rand(0, count($users) - 1)]['id']);
                $comment->setArticleId($article['id']);
                $comment->setTitle($explode[rand(1, count($explode) - 1)] . ' ' . $explode[rand(1, count($explode) - 1)]);
                $comment->setContent(substr($contentComment, rand(0, 50), rand(100, strlen($contentComment))));
                $comment->save();
            }
            // et un nombre de like aleatoire
            for ($k = 0; $k < $rand2; $k++) {
                $like = new LikeModel();
                $like->setUserId($users[rand(0, count($users) - 1)]['id']);
                $like->setArticleId($article['id']);
                $like->save();
            }
        }

        $comments = $commentManager->getAll();
        foreach ($comments as $comment) {
            $rand2 = rand(1, 3);
            // pour chaque commentaire on crée un nombre de reponse aleatoire
            for ($j = 0; $j < $rand2; $j++) {
                $reply = new CommentModel();
                // setParent permet d'identifier le commentaire initial (le parent)
                $reply->setParentId($comment['id']);
                $reply->setAuthorId($users[rand(0, count($users) - 1)]['id']);
                $reply->setArticleId($comment['article_id']);
                $reply->setTitle($explode[rand(1, count($explode) - 1)] . ' ' . $explode[rand(1, count($explode) - 1)]);
                $reply->setContent(substr($contentComment, rand(0, 50), rand(100, strlen($contentComment))));
                $reply->save();
            }
        }

        // liste des pages presentes dans ce theme
        $arrayPages = ['Home' => 'home', 'Articles' => 'articles', 'Presentation' => 'presentation', 'Contact' => 'contact', 'About' => 'about'];
        $itemManager = new MenuItemsModel();
        $pageManager = new PageModel();
        $pageManager->truncate('page');
        $itemManager->truncate('menuitem');

        $position = 1;
        // creation des pages
        foreach ($arrayPages as $key => $value) {
            $page = new PageModel();
            $page->setTitle($key);
            $page->setType($value);
            $page->setLink('/' . $value);
            $page->setThemeId(1);
            $page->save();

            // creation des items du menu nav
            $item = new MenuItemsModel();
            $item->setLink('/' . $value);
            $item->setName($key);
            // la position definit l'ordre d'affichage, updatable par admin
            $item->setPosition($position);
            $item->save();

            $position++;
        }
    }

    /**
    * créations des models pour le thème tennis
    */
    public function loadThemeTwentyOneSports()
    {
        $theme = new ThemeModel();
        $theme->truncate('theme');
        $theme->setDescription("Un thème blogging dédié au foot");
        $theme->setName('Twenty foot theme');
        $theme->setImage('footTheme.png');
        $theme->save();

        $theme = new ThemeModel();
        $theme->setDescription("Un thème moderne et élégant, proposant des articles Tennis , un système d'inscription pour réagir sur des articles d'actualité et poster ses propres articles");
        $theme->setName('Twenty one tennis');
        $theme->setImage('tennis.jpg');
        $theme->save();


        $categoryManager = new Categorie();
        $categoryManager->truncate('categorie');
        $categorieNames = ['Grand Chelem' => 'wimbledon.jpg', 'NextGen' => 'nextgen.png', 'Tennis Francais' => 'garcia.jpg', 'Tennis feminin' => 'mladenovic.jpg', 'Les ATP 500' => '500.jpg'];
        foreach ($categorieNames as $key => $value) {
            $category = new Categorie();
            $category->setName($key);
            $category->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum');
            $category->setImage($value);
            $category->setSlug(Slugger::sluggify(strtolower($key)));
            $category->save();

            $params['route'] = Slugger::sluggify($key);
            $params['role'] = 'public' ?? null;
            $params['model'] = 'categories' ?? null;
            $params['action'] = 'categoryPage';
            $this->writeRoute($params);
        }

        $articleManager = new ArticleModel();
        $articleManager->truncate('article');
        $categories = $categoryManager->getAll();
        $content = "CET ARTICLE EST UN MODÈLE D'EXEMPLE : Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.";
        $content = str_replace(',', '', $content);
        $content = str_replace('.', '', $content);
        $arrayContent =  explode(' ', $content);

        for ($i = 0; $i < count($categories); $i++) {
            for ($j = 0; $j < 2; $j++) {
                $title = $arrayContent[rand(0, count($arrayContent) - 1)] . ' ' . $arrayContent[rand(0, count($arrayContent) - 1)] . ' ' . $arrayContent[rand(0, count($arrayContent) - 1)];
                $article = new ArticleModel();
                $article->setCategoryId($categories[$i]['id']);
                $article->settitle($title);
                $article->setContent($content);
                $article->setImage("https://loremflickr.com/640/420/tennis?random={$j}");
                $article->setSlug(Slugger::sluggify($title));
                $article->save();
            }
        }

        $userManager = new User();
        $userManager->truncate('user');

        $articles = $articleManager->getAll();
        $users = $userManager->getAll();
        $commentManager = new CommentModel();
        $likeManager = new LikeModel();
        $commentManager->truncate('comment');
        $likeManager->truncate('like');
        $contentComment = "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatu unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab ";
        $contentComment = str_replace(',', '', $contentComment);
        $contentComment = str_replace('.', '', $contentComment);
        $explode = explode(' ', $contentComment);

        foreach ($articles as $article) {
            $rand = rand(2, 10);
            $rand2 = rand(1, 3);

            for ($j = 0; $j < $rand; $j++) {
                $comment = new CommentModel();
                $comment->setParentId(null);
                $comment->setAuthorId($users[rand(0, count($users) - 1)]['id']);
                $comment->setArticleId($article['id']);
                $comment->setTitle($explode[rand(1, count($explode) - 1)] . ' ' . $explode[rand(1, count($explode) - 1)]);
                $comment->setContent(substr($contentComment, rand(0, 50), rand(100, strlen($contentComment))));
                $comment->save();
            }
            for ($k = 0; $k < $rand2; $k++) {
                $like = new LikeModel();
                $like->setUserId($users[rand(0, count($users) - 1)]['id']);
                $like->setArticleId($article['id']);
                $like->save();
            }
        }

        $comments = $commentManager->getAll();
        foreach ($comments as $comment) {
            $rand2 = rand(1, 3);
            for ($j = 0; $j < $rand2; $j++) {
                $reply = new CommentModel();
                $reply->setParentId($comment['id']);
                $reply->setAuthorId($users[rand(0, count($users) - 1)]['id']);
                $reply->setArticleId($comment['article_id']);
                $reply->setTitle($explode[rand(1, count($explode) - 1)] . ' ' . $explode[rand(1, count($explode) - 1)]);
                $reply->setContent(substr($contentComment, rand(0, 50), rand(100, strlen($contentComment))));
                $reply->save();
            }
        }

        $arrayPages = ['Home' => 'home', 'Articles' => 'articles', 'Presentation' => 'presentation', 'Contact' => 'contact', 'About' => 'about'];
        $itemManager = new MenuItemsModel();
        $pageManager = new PageModel();
        $pageManager->truncate('page');
        $itemManager->truncate('menuitem');

        $position = 1;
        foreach ($arrayPages as $key => $value) {
            $page = new PageModel();
            $page->setTitle($key);
            $page->setType($value);
            $page->setLink('/' . $value);
            $page->setThemeId(2);
            $page->save();

            $item = new MenuItemsModel();
            $item->setLink('/' . $value);
            $item->setName($key);
            $item->setPosition($position);
            $item->save();

            $position++;
        }
    }

    private function writeRoute(array $params): void
    {
        $content = file_get_contents('routes.yml');
        $content .= "\n\n/" . strtolower($params['route']) . ':';
        $content .= "\n  controller: " . $params['model'];
        $content .= "\n  action: " . $params['action'];
        $content .= "\n  role: [" . $params['role'] . "]";
        file_put_contents('routes.yml', $content);
    }
}