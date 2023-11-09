$(document).ready(function () {


    // lofin form hide show


    $('#login_open').click(function () {

        let loginBox = document.querySelector('#my_form');

        if (loginBox.style.display === '' || loginBox.style.display === 'none') {
            loginBox.style.display = "block";
        } else {
            loginBox.style.display = "none";
        }
    })




    // login form check validate

    let loginForm = document.querySelector("#login_form"),
        eFiled = loginForm.querySelector(".email"),
        eInput = eFiled.querySelector("input"),

        pFiled = loginForm.querySelector(".pass"),
        pInput = pFiled.querySelector("input");

    loginForm.onsubmit = (e) => {
        e.preventDefault();

        if (eInput.value == "" && pInput.value == "") {
            document.getElementById("error_login_email").style.display = "block";
            document.getElementById("email").style.border = "1px solid red";

            document.getElementById("error_login_psw").style.display = "block";
            document.getElementById("psw").style.border = "1px solid red";

        } else if (eInput.value == "") {
            document.getElementById("error_login_email").style.display = "block";
            document.getElementById("email").style.border = "1px solid red";
        } else if (pInput.value == "") {
            document.getElementById("error_login_psw").style.display = "block";
            document.getElementById("psw").style.border = "1px solid red";
        } else {

            var dataForm = $("#login_form").serializeArray();

            $.ajax({
                url: "ajax",
                type: 'POST',  // method
                cache: false,
                dataType: "json",
                // data: {'email': eInput.value, 'psw': pInput.value},
                data: dataForm,
                success: function (data, status, xhr) {
                    console.log(data[0]);

                    if (data.length == 0) {
                        window.location.reload();
                        window.location.href = window.location.href;
                    }
                    else {
                        $("#error_login_psw_form").html("");
                        for (n in data) {
                            $("#error_login_psw_form").append('<p class="error_txt " style="display:block;">' + data[n] + '</p>');
                        }

                        document.getElementById("email").style.border = "1px solid red";
                        document.getElementById("psw").style.border = "1px solid red";
                    }

                },
                error: function (xhr) {
                    // alert(xhr.statusText);
                    alert("data error");
                }
            });
        }

        eInput.onkeyup = () => {
            //email
            let pattern = /[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/;
            if (eInput.value.match(pattern)) {
                document.getElementById("error_login_email").style.display = "none";
                document.getElementById("email").style.border = "1px solid lightgray";
            }
        }

        pInput.onkeyup = () => {
            if (pInput.value.length > 3) {
                document.getElementById("error_login_psw").style.display = "none";
                document.getElementById("psw").style.border = "1px solid lightgray";

            }
        }

    }


    //  search
    // let openCloseSearch = document.querySelector("#search");

    // openCloseSearch.addEventListener("click", function () {
    //     let loginBox = document.querySelector('.search_form');
    //     if (loginBox.style.display === '' || loginBox.style.display === 'none') {
    //         loginBox.style.display = "block";
    //     } else {
    //         loginBox.style.display = "none";
    //     }
    // });


    $("#search").click(function () {
        $(".search_form").toggleClass("d-none");
    });


    // header search
    let searchFormHeader = document.querySelector("#search_form_header"),
        headerInput = searchFormHeader.querySelector("input");

    searchFormHeader.onsubmit = (e) => {
        if (headerInput.value.length == 0) {
            e.preventDefault();
            document.querySelector(".error_txt").style.display = "block";
            document.querySelector(".search_input").style.border = "1px solid red";
            document.querySelector(".error_txt").innerHTML = "Cannot be empty ";
        }

        if (headerInput.value.length < 2 && headerInput.value.length != 0) {
            e.preventDefault();
            document.querySelector(".error_txt").style.display = "block";
            document.querySelector(".search_input").style.border = "1px solid red";
            document.querySelector(".error_txt").innerHTML = "Minimum symbol 3";
        }

        headerInput.onkeyup = () => {
            if (headerInput.value.length > 2) {
                document.querySelector(".search_input").style.border = "1px solid lightgray";
                document.querySelector(".error_txt").style.display = "none";
            }
        }

    };

    /*
    $( "#search_form_header" ).submit(function(e) {
        let errors = [];
        let searchVal = $(this).find("input").val().trim();
        
        if(searchVal == ''){
            errors.push("Cannot be empty");
        }
        else if(searchVal.length < 2){
            errors.push("Minimum 3 symbols");
        }

        if(errors.length > 0) {
            e.preventDefault();
            $(".error_txt").show().css("border", "1px solid red");
        }
    });
    */


    // Subscribe

    $('#subscribe_btn').click(function (e) {
        e.preventDefault();


        let error = [];


        let subName = $("#subscribe_name").val().trim();
        let subEmail = $("#subscribe_email").val().trim();

        if (subName == '') {
            // e.preventDefault();

            error.push("Name empty");
            $("#subscribe_name").css("border", "1px solid red");
            $('#subscribe_name').attr("placeholder", "Name emtpy");
            $("#subscribe_name").addClass('red');
        }
        if (subEmail == '') {
            // e.preventDefault();
            error.push("Email empty");

            $("#subscribe_email").css("border", "1px solid red");
            $('#subscribe_email').attr("placeholder", "Email emtpy");
            $("#subscribe_email").addClass('red');

        }


        if (error.length == 0) {
            // e.preventDefault();
    
            var dataForm = $(".sub_form").serializeArray();
            console.log(dataForm);
            $.ajax({
                url: "ajax",
                type: 'POST',  // method
                cache: false,
                dataType: "json",
                data: dataForm,
                success: function (data, status, xhr) {
    
                    console.log(data.error);
    
                    if (data.error == "subscribed") {
                        $(".error_txt_sub").css('display', 'block');
                    } 
                    
                    if(data.error == "successfully") {
    
                        $(".sub_form").html("");
    
                        let contactForm = document.querySelector('.sub_form');
    
                        contactForm.innerHTML = `
                            <div class="p-3 mb-2 bg-success text-white text-center">SUBSCRIBED</div>
                        `;
                        setTimeout(function() {
                            window.location.reload();
                         }, 2000);
    
                    }
    
                },
                error: function (xhr) {
                    alert("data error");
                }
            });
        }


        $('.subscribe input').click(function () {
            $(this).css("border", "1px solid #e9e9e9");
            $(this).removeClass('red');

            let input = $(this).find('input');
            let inputId = input.prevObject["0"].id;
            console.log(inputId);

            if (inputId == "subscribe_name") {
                $('#subscribe_name').attr("placeholder", "Your name");
            }
            if (inputId == "subscribe_email") {
                $('#subscribe_email').attr("placeholder", "Your email address");
            }

        });
    });
});