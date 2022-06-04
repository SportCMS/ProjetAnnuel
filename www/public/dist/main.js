console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
var article = document.querySelector('#heart_like').dataset.article;
$.ajax({
  type: "POST",
  url: 'http://localhost:81/getLikes?',
  headers: {
    "Access-Control-Allow-Origin": "*"
  },
  data: "article=".concat(article),
  success: function success(rep) {
    var response = JSON.parse(rep);

    if (response.articleLikes == 0) {
      $('#heart_like').addClass('unliked');
    } else {
      $('#heart_like').addClass('liked');
    }
  }
});
$('#heart_like').on('click', function () {
  var article = document.querySelector('#heart_like').dataset.article;

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
      "Access-Control-Allow-Origin": "*"
    },
    data: "action=like&article=".concat(article),
    success: function success(rep) {
      var response = JSON.parse(rep);
      $('#count_like').html(response.count_likes);
    }
  });
}); // reply un commentaire

document.querySelectorAll(".reply").forEach(function (reply) {
  reply.addEventListener('click', function () {
    textarea = document.createElement('textarea');
    textarea.setAttribute('id', 'textareaReply');
    textarea.setAttribute('maxlength', 400);
    textarea.setAttribute('cols', 50);
    textarea.setAttribute('rows', 4);
    textarea.style.display = 'block';
    textarea.style.resize = 'none';
    reply.parentNode.appendChild(textarea);
    var buttonReply = document.createElement('button');
    buttonReply.setAttribute('id', 'buttonReply');
    buttonReply.innerHTML = 'reply';
    reply.parentNode.appendChild(buttonReply);
    reply.style.display = 'none';
    $('#buttonReply').on('click', function () {
      if ($('#textareaReply').val().length == 0) return;
      var replyContent = $('#textareaReply').val();
      var userId = reply.dataset.replyer;
      var parentId = reply.dataset.parentid;
      var articleId = reply.dataset.article;
      $.ajax({
        type: "POST",
        url: 'http://localhost:81/replyComment?',
        headers: {
          "Access-Control-Allow-Origin": "*"
        },
        data: "replyContent=".concat(replyContent, "&userId=").concat(userId, "&parentId=").concat(parentId, "&articleId=").concat(articleId),
        success: function success(rep) {
          var response = JSON.parse(rep);
          var parentBlock = document.getElementById('replyComment' + parentId);
          $go = "\n                        <br>\n                        <br>\n                        <div style=\"margin-left:50px;padding:8px 0; border-top:1px solid black\">\n                            <small style=\"font-weight:bold;font-style:italic\">r\xE9pondu par ".concat(response.comment.firstname, " ").concat(response.comment.lastname, " le\n                                ").concat(response.comment.date, " \xE0 ").concat(response.comment.hour, "\n                            </small>\n                            <div style=\"display:flex;align-items:center;margin-top:10px;\">\n                                <span style=\"padding:5px;border-radius:50%;background:grey;color:white\">\n                                    ").concat(response.comment.firstname[0].toUpperCase(), " ").concat(response.comment.lastname[0].toUpperCase(), "\n                                </span>\n                                &nbsp;\n                                <small class=\"contentCommentSpan\">\n                                    ").concat(response.comment.content, "\n                                </small>\n                                &nbsp;\n                            </div>\n                        </div>\n                        ");
          $('#replyComment' + parentId).append($go);
        }
      });
    });
  });
}); // laisser un commentaire

$('#postComment').on('click', function () {
  $('#formComment').fadeIn('slow');
  $('#send').on('click', function () {
    if ($('#contentComment').val().length == 0 || $('#titleComment').val().length == 0) {
      $('#alertComment').css('color', 'red');
      $('#alertComment').html('Veuillez remplir tous les champs');
      return;
    }

    var title = $('#titleComment').val();
    var content = $('#contentComment').val();
    var article = document.querySelector('#formComment').dataset.article;
    $.ajax({
      type: "POST",
      url: 'http://localhost:81/comment?',
      headers: {
        "Access-Control-Allow-Origin": "*"
      },
      data: "title=".concat(title, "&content=").concat(content, "&article=").concat(article),
      success: function success(rep) {
        var response = JSON.parse(rep);

        if (response.error != null) {
          $('#alertComment').css('color', 'red');
          $('#alertComment').html(response.error);
        }

        if (response.success != null) {
          $('#alertComment').css('color', 'green');
          $('#alertComment').html(response.success);
        }

        $('#titleComment').val('');
        $('#contentComment').val('');
        $('.comments').append("\n                    <hr>\n                    <small style=font-weight:bold;font-style:italic>Comment\xE9 par ".concat(response.comment.firstname, " ").concat(response.comment.lastname, " le\n                       ").concat(response.comment.date, " \xE0 ").concat(response.comment.hour, "\n                    </small>\n                    <div style=\"display:flex;align-items:center;margin-top:10px;\">\n                        <span style=\"padding:10px;border-radius:50%;background:grey;color:white\">\n                            ").concat(response.comment.firstname[0].toUpperCase(), " ").concat(response.comment.lastname[0].toUpperCase(), "\n                        </span>\n                        &nbsp;&nbsp;\n                        <h3 style=\"margin-top:15px\"> ").concat(response.comment.title, "</h3>\n                    </div>\n                    <br>\n                    <span class=\"contentCommentSpan\">\n                         ").concat(response.comment.content, "\n                    </span>\n                    "));
      }
    });
  });
});
