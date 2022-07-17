let article = document.querySelector('#heart_like').dataset.article // on récupère l'id de l'article

$.ajax({
    type: "POST",
    url: 'http://localhost:81/getLikes?',
    headers: {
        "Access-Control-Allow-Origin": "*",
    },
    data: `article=${article}`,
    success: function (rep) {
        let response = JSON.parse(rep)

        if (response.articleLikes == 0) {
            $('#heart_like').addClass('unliked') // si l'article n'a pas de like, on ajoute la classe unliked
        } else {
            $('#heart_like').addClass('liked') // si l'article a de like, on ajoute la classe liked
        }
    }
});

$('#heart_like').on('click', () => { 

    let article = document.querySelector('#heart_like').dataset.article // on récupère l'id de l'article

    if ($('#heart_like').hasClass('liked')) { 
        $("#heart_like").removeClass("liked"); // si l'article possède un like, on retire la classe liked
        $('#heart_like').addClass("unliked"); // et on ajoute la classe unliked
    } else {
        $('#heart_like').addClass("liked"); // si l'article n'a pas de like, on ajoute la classe liked
        $("#heart_like").removeClass("unliked"); // et on retire la classe unliked
    }

    $.ajax({
        type: "POST",
        url: 'http://localhost:81/createLike?',
        headers: {
            "Access-Control-Allow-Origin": "*",
        },
        data: `action=like&article=${article}`,
        success: function (rep) {
            let response = JSON.parse(rep)
            $('#count_like').html(response.count_likes)

        }
    });

});