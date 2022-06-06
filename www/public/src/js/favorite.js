$('star_favorite').on('click', function(e) {
    e.preventDefault();
    let article = $(this).data('article');
    let action = $(this).data('action');
    let url = 'http://localhost:81/createFavorite?action=' + action + '&article=' + article;
    $.ajax({
        type: "POST",
        url: url,
        headers: {
            "Access-Control-Allow-Origin": "*",
        },
        success: function(rep) {
            let response = JSON.parse(rep);
            if (response.action == 'favorite') {
                $('#star_favorite').addClass('favorited');
            } else {
                $('#star_favorite').removeClass('favorited');
            }
            $('#count_favorite').html(response.count_favorites);
        }
    });
});