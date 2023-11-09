$('#pass_btn').click(function (e) {
    e.preventDefault();

    let changePassError = [];

    let psw1 = $("#psw1").val().trim();
    let psw2 = $("#psw2").val().trim();

    if (psw1 == '') {
        changePassError.push("password empty");
        $("#psw1").css({ "border": "1px solid red", "border-radius": "3px" });
    }

    if (psw2 == '') {
        changePassError.push("Re-enter-password empty");
        $("#psw2").css({ "border": "1px solid red", "border-radius": "3px" });
    }

    if (psw1.length < 5) {
        changePassError.push("password short");
        $("#psw1").css({ "border": "1px solid red", "border-radius": "3px" });
    }

    if (psw2.length < 5) {
        changePassError.push("password short");
        $("#psw2").css({ "border": "1px solid red", "border-radius": "3px" });
    }

    if (changePassError.length === 0) {
        var dataForm = $("#pass_change").serializeArray();
        dataForm.push({ name: "action", value: "change" });

        $.ajax({
            url: "ajax",
            type: 'POST',  // method
            cache: false,
            dataType: "json",
            data: dataForm,
            success: function (data, status, xhr) {
                console.log(data);

                if (data == '') {
                    // alert("change");
                    $(".modal-content").html("");

                    let passChangeForm = document.querySelector('.modal-content');

                    passChangeForm.innerHTML = `
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                        </div>

                        <div class="modal-body">
                            <div class="contact_form__sent">
                                <p>Changed</p>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <div class="form_box">
                                <input type="submit" name="pass_change" data-bs-dismiss="modal" id="pass_btn_changeed" value="Ok">
                            </div>
                        </div>
                    `;
                } else {
                    alert("error");
                }

                // if (data.error == 'Mail send') {
                //     $(".comment_form").html("");

                //     let contactForm = document.querySelector('.comment_form');

                //     contactForm.innerHTML = `
                //         <div class="contact_form__sent">
                //             <p>Your Message Sent</p>
                //         </div>
                //         <div class="contact_form__btn">
                //         <button type="submit"><a href=".">Go Back</a></button>
                //         </div>
                //     `;
                // }
            },
            error: function (xhr) {
                $(".error_txt_contact").css('display', 'block')
            }
        });
    }


    // clear input error 
    $('#pass_change input').keyup(function () {
        console.log(this);
        $(this).css({ "border": "1px solid #767676", "border-radius": "3px" });
    });

});