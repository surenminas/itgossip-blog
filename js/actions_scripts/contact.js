// emeil check
let regVal = /[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/;


let contactForm = document.querySelector(".contact_form"),
    nFiled = contactForm.querySelector("#contact_form_name"),
    nInput = nFiled.querySelector("input"),

    eFiled = contactForm.querySelector("#contact_form_email"),
    eInput = eFiled.querySelector("input"),

    tFiled = contactForm.querySelector("#contact_form_text"),
    tInput = tFiled.querySelector("textarea");


contactForm.onsubmit = (e) => {
    e.preventDefault();

    let error = [];


    if (nInput.value == "" && eInput.value == "" && tInput.value == "") {
        error.push('all fileds empty');
        document.querySelector(".error_txt").style.display = "block";

        document.getElementById("contact_name").style.border = "1px solid red";
        document.getElementById("contact_email").style.border = "1px solid red";
        document.getElementById("contact_text").style.border = "1px solid red";

    }

    if (nInput.value == "") {
        error.push('name empty');

        document.querySelector(".error_txt").style.display = "block";
        document.getElementById("contact_name").style.border = "1px solid red";
    }

    if (eInput.value == "") {
        error.push('email empty');

        document.querySelector(".error_txt").style.display = "block";
        document.getElementById("contact_email").style.border = "1px solid red";
    }

    if (tInput.value == "") {
        error.push('text empty');

        document.querySelector(".error_txt").style.display = "block";
        document.getElementById("contact_text").style.border = "1px solid red";
    }


    if (error.length == 0) {
        var dataForm = $(".contact_form").serializeArray();
        $.ajax({
            url: "ajax",
            type: 'POST',  // method
            cache: false,
            dataType: "json",
            data: dataForm,
            success: function (data, status, xhr) {
                if (data.error == 'Mail send') {
                    $(".comment_form").html("");

                    let contactForm = document.querySelector('.comment_form');

                    contactForm.innerHTML = `
                        <div class="contact_form__sent">
                            <p>Your Message Sent</p>
                        </div>
                        <div class="contact_form__btn">
                        <button type="submit"><a href=".">Go Back</a></button>
                        </div>
                    `;
                }
            },
            error: function (xhr) {
                $(".error_txt_contact").css('display', 'block')
            }
        });
    }
}



$('#comment_form input, #comment_form textarea, #comment_form select').click(function () {
    $(this).css("border", "1px solid #e9e9e9");
});