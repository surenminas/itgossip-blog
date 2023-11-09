
$('.comment_form').submit(function (e) {
    const commentForm = $(this);
    e.preventDefault();
    const clearInputAfterInser = $(this).find("textarea[name='text']").val();


    if (clearInputAfterInser == '') {
        $("#comment_text").css("border", "1px solid red");
    } else {

        const dataForm = $(this).find("form").serializeArray();
        dataForm.push({ name: "action", value: "add_comment" });

        $.ajax({
            method: "POST",
            url: "ajax",
            data: dataForm,
            success: function (data) {
                if (data != "") {
                    $('.error').html(`<div class='alert alert-danger'>${data}</div>`);
                } else {
                    let commentTemplate = $(".wrap_comment_block").last().clone();
                    commentTemplate.removeAttr("style");
                    commentTemplate.find(".comment_content__text p").text(commentForm.find("#comment_text").val());
                    $(".comment_block").prepend(commentTemplate);
                }

                $('.comment_form').find('textarea').val('');
            },
            error: function (xhr) {
                alert(xhr.statusText);
                alert('data base error');

            }
        });


    };
    $('#comment_text').keyup(function () {
        $(this).css("border", "1px solid #e9e9e9");
    });


});



// toggle reply button >>>
$(".reply").click(function () {
    let showInput = $(this).next('.reply_block');
    console.log(this);
    $(showInput).toggleClass("d-none");
});
// toggle reply button <<<



$('.reply_block').submit(function (e) {
    e.preventDefault();
    const commentFormReply = $(this);

    console.log(commentFormReply);

    let clearInputAfterInserReply = $(this).find("input[name='text']").val();

    if (clearInputAfterInserReply == '') {
        $(".reply_block__text").css("border", "1px solid red");
    } else {

        const dataFormReply = $(this).find("form").serializeArray();
        dataFormReply.push({ name: "action", value: "add_comment" });

        $.ajax({
            method: "POST",
            url: "ajax",
            data: dataFormReply,
            success: function (data) {
                if (data != "") {
                    alert("eror" + data);
                    // $('.error').html(`<div class='alert alert-danger'>${data}</div>`);
                } else {
                    let commentTemplate = $(".comment_content_reply").last().clone();
                    // commentTemplate.removeAttr("style");
                    commentTemplate.find(".comment_content__text p").text(commentFormReply.find(".reply_block__text").val());
                    commentFormReply.parents(".wrap_comment_block").prepend(commentTemplate);

                }

                $('.reply_block__text').find('input').val('')
            },
            error: function (xhr) {
                alert(xhr.statusText);
                alert('data base error');

            }
        });


    };
    $('#comment_text').keyup(function () {
        $(this).css("border", "1px solid #e9e9e9");
    });
})

