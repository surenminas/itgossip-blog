// emeil check
let pattern = /[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/;

//forgot Email validation
$('#forgot').click(function (e) {

    let forgorEmail = $('#email_forgot').val().trim();


    if (forgorEmail == '') {
        e.preventDefault();
        $('#email_forgot').css("border", "1px solid red");
    } else {
        if (!forgorEmail.match(pattern)) {
            alert('email');
            e.preventDefault();
            $('#forgot_err').html("<p>Введите правильный Email</p>");
        }
    }

    $('#email_forgot').keyup(function () {
        $(this).css("border", "1px solid #e9e9e9");

        console.log(forgorEmail);


        // if (forgorEmail.match(pattern)) {
        //     alert('ok');
        //     document.getElementById("error_login_email").style.display = "none";
        //     $('#email_forgot').css("border", "1px solid red");
        // }
    });



});