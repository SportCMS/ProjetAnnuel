let article = document.querySelector('#heart_like').dataset.article

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
            $('#heart_like').addClass('unliked')
        } else {
            $('#heart_like').addClass('liked')
        }
    }
});

$('#heart_like').on('click', () => {

    let article = document.querySelector('#heart_like').dataset.article

    if ($('#heart_like').hasClass('liked')) {
        $("#heart_like").removeClass("liked");
        $('#heart_like').addClass("unliked");
    } else {
        $('#heart_like').addClass("liked");
        $("#heart_like").removeClass("unliked");
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