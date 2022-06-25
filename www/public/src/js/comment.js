// reply un commentaire
document.querySelectorAll(".reply").forEach(function (reply) {
    reply.addEventListener('click', function () {
        textarea = document.createElement('textarea')
        textarea.setAttribute('id', 'textareaReply');
        textarea.setAttribute('maxlength', 400);
        textarea.setAttribute('cols', 50);
        textarea.setAttribute('rows', 4);
        textarea.style.display = 'block';
        textarea.style.resize = 'none';
        reply.parentNode.appendChild(textarea)

        let buttonReply = document.createElement('button');
        buttonReply.setAttribute('id', 'buttonReply');
        buttonReply.innerHTML = 'reply'
        reply.parentNode.appendChild(buttonReply)

        reply.style.display = 'none';

        $('#buttonReply').on('click', () => {
            if ($('#textareaReply').val().length == 0)
                return;

            let replyContent = $('#textareaReply').val();
            let userId = reply.dataset.replyer
            let parentId = reply.dataset.parentid
            let articleId = reply.dataset.article


            $.ajax({
                type: "POST",
                url: 'http://localhost:81/replyComment?',
                headers: {
                    "Access-Control-Allow-Origin": "*",
                },
                data: `replyContent=${replyContent}&userId=${userId}&parentId=${parentId}&articleId=${articleId}`,
                success: function (rep) {
                    let response = JSON.parse(rep)
                    let parentBlock = document.getElementById('replyComment' + parentId)
                    
                    $('#textareaReply').html('');
                    $('#textareaReply').fadeOut();

                    $go = `
                        <br>
                        <br>
                        <div style="margin-left:50px;padding:8px 0; border-top:1px solid black">
                            <small style="font-weight:bold;font-style:italic">répondu par ${response.comment.firstname} ${response.comment.lastname} le
                                ${response.comment.date} à ${response.comment.hour}
                            </small>
                            <div style="display:flex;align-items:center;margin-top:10px;">
                                <span style="padding:5px;border-radius:50%;background:grey;color:white">
                                    ${response.comment.firstname[0].toUpperCase()} ${response.comment.lastname[0].toUpperCase()}
                                </span>
                                &nbsp;
                                <small class="contentCommentSpan">
                                    ${response.comment.content}
                                </small>
                                &nbsp;
                            </div>
                        </div>
                        `
                    $('#replyComment' + parentId).append($go)

                }
            });
        });
    });
});

// laisser un commentaire
$('#postComment').on('click', () => {
    $('#formComment').fadeIn('slow');

    $('#send').on('click', () => {
        if ($('#contentComment').val().length == 0 || $('#titleComment').val().length == 0) {
            $('#alertComment').css('color', 'red')
            $('#alertComment').html('Veuillez remplir tous les champs');
            return;
        }

        let title = $('#titleComment').val();
        let content = $('#contentComment').val();
        let article = document.querySelector('#formComment').dataset.article;

        $.ajax({
            type: "POST",
            url: 'http://localhost:81/comment?',
            headers: {
                "Access-Control-Allow-Origin": "*",
            },
            data: `title=${title}&content=${content}&article=${article}`,
            success: function (rep) {
                let response = JSON.parse(rep)
                if (response.error != null) {
                    $('#alertComment').css('color', 'red')
                    $('#alertComment').html(response.error);
                }
                if (response.success != null) {
                    $('#alertComment').css('color', 'green')
                    $('#alertComment').html(response.success);
                }

                $('#titleComment').val('');
                $('#contentComment').val('');

              


                $('.comments').append(
                    `
                    <hr>
                    <small style=font-weight:bold;font-style:italic>Commenté par ${response.comment.firstname} ${response.comment.lastname} le
                       ${response.comment.date} à ${response.comment.hour}
                    </small>
                    <div style="display:flex;align-items:center;margin-top:10px;">
                        <span style="padding:10px;border-radius:50%;background:grey;color:white">
                            ${response.comment.firstname[0].toUpperCase()} ${response.comment.lastname[0].toUpperCase()}
                        </span>
                        &nbsp;&nbsp;
                        <h3 style="margin-top:15px"> ${response.comment.title}</h3>
                    </div>
                    <br>
                    <span class="contentCommentSpan">
                         ${response.comment.content}
                    </span>
                    `
                )
            }
        });
    })
});