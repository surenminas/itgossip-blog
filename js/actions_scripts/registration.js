$('#reg_btn').click(function (e) {

    let error = [];


    let regName = $("#name_rgn").val().trim();
    let regEmail = $("#email_rgn").val().trim();
    let regPsw1 = $("#psw1_rgn").val().trim();
    let regPsw2 = $("#psw2_rgn").val().trim();



    if (regName == '') {
        e.preventDefault();

        error.push("Name empty");
        $("#name_rgn").css("border", "1px solid red");
        $('#name_rgn').attr("placeholder", "Name field cannot be emtpy");
        $("#name_rgn").addClass('red');
    }
    if (regEmail == '') {
        e.preventDefault();
        error.push("Email empty");

        $("#email_rgn").css("border", "1px solid red");
        $('#email_rgn').attr("placeholder", "Email field cannot be emtpy");
        $("#email_rgn").addClass('red');

    }
    if (regPsw1 == '') {
        e.preventDefault();
        error.push("Password empty");

        $("#psw1_rgn").css("border", "1px solid red");
        $('#psw1_rgn').attr("placeholder", "Password field cannot be emtpy");
        $("#psw1_rgn").addClass('red');
    }
    if (regPsw2 == '') {
        e.preventDefault();
        error.push("Password2 empty");

        $("#psw2_rgn").css("border", "1px solid red");
        $('#psw2_rgn').attr("placeholder", "Password field cannot be emtpy");
        $("#psw2_rgn").addClass('red');
    }

    if (regPsw1 != regPsw2) {
        e.preventDefault();

        error.push("Passwords not equals");

        $("#psw1_rgn").css("border", "1px solid red");
        $('#psw1_rgn').attr("placeholder", "Password field cannot be emtpy");
        $("#psw1_rgn").addClass('red');

        $("#psw2_rgn").css("border", "1px solid red");
        $('#psw2_rgn').attr("placeholder", "Password field cannot be emtpy");
        $("#psw2_rgn").addClass('red');
    }

    if (error.length == 0) {
        e.preventDefault();

        var dataForm = $(".reg-form").serializeArray();
        console.log(dataForm);
        $.ajax({
            url: "ajax",
            type: 'POST',  // method
            cache: false,
            dataType: "json",
            data: dataForm,
            success: function (data, status, xhr) {

                console.log(data.error);

                if (data.error == "email busy") {
                    $("#email_rgn").css("border", "1px solid red");
                    $(".error_txt_reg").css('display', 'block');
                } else {

                    $(".reg_form").html("");

                    let contactForm = document.querySelector('.reg_form');

                    contactForm.innerHTML = `
                    <div class="comment_form">
                        <div class="contact_form__sent">
                            <p>Registered</p>
                        </div>
                        <div class="contact_form__btn">
                            <button type="submit" name="register"><a href=".">Go Back</a></button>
                        </div>
                    </div>
                    `;

                }

            },
            error: function (xhr) {
                alert("data error");
            }
        });
    }

    $('.reg_form__form input').click(function () {
        $(this).css("border", "1px solid #e9e9e9");
        $(this).removeClass('red');

        let input = $(this).find('input');
        let inputId = input.prevObject["0"].id;

        if (inputId == "name_rgn") {
            $('#name_rgn').attr("placeholder", "Your Name*");
        }
        if (inputId == "email_rgn") {
            $('#email_rgn').attr("placeholder", "Your Email*");
            $('.error_txt').css('display', 'none');

        }
        if (inputId == "psw1_rgn") {
            $('#psw1_rgn').attr("placeholder", "Password*");
        }
        if (inputId == "psw2_rgn") {
            $('#psw2_rgn').attr("placeholder", "Re-enter Password*");
        }

    });


});
