let article = document.querySelector('#star_favorite').dataset.article;

$.ajax({
    type: "POST",
    url: 'http://localhost:81/getFavorites?',
    headers: {
        "Access-Control-Allow-Origin": "*",
    },
    data: `article=${article}`,
    success: function (rep) {
        let response = JSON.parse(rep)

        if(response.articleFavorites == 0){
            alert('Article ajouté en favoris');
        }
        else{
            alert('Problème d\'ajout de l\'article à vos favoris');
        }
    }
})

$('#star_favorite').on('click', function(e) {
    e.preventDefault();
    let article = document.querySelector('#star_favorite').dataset.article

    $.ajax({
        type: "POST",
        url: 'http://localhost:81/createFavorite?',
        headers: {
            "Access-Control-Allow-Origin": "*",
        },
        data: `action=favorite&article=${article}`,
        success: function (rep) {
            let response = JSON.parse(rep)
            $('#count_favorite').html(response.count_favorites)

        }
    });
});