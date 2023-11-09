// ...  short $(function(){...}
$(document).ready(function () {

    // emeil check
    let regVal = /[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}/;

    // search form validation
    $('#search_form').submit(function (e) {
        let searchText = $('#search_text').val();
        $('#search_err p').remove();

        if (searchText == '') {
            e.preventDefault();
            $('#search_err').html('<p>Поле не может быть пустым</p>');
        } else if (searchText.length < 4) {
            e.preventDefault();
            $('#search_err').html('<p>Не менее 4 символов</p>');
        }
    });

    // login form validation
    $('#login_form').submit(function (event) {
        let login = $('#login').val();
        let password = $('#password').val();
        $('#login_err p').remove();

        if (login == '' && password == '') {
            event.preventDefault();
            $('#login_err').html("<p>Поле логин и пароль не должно быть пустым</p>");
        } else if (login == '') {
            event.preventDefault();
            $('#login_err').html("<p>Поле логин не должно быть пустым</p>");
        } else if (password == '') {
            event.preventDefault();
            $('#login_err').html("<p>Поле пароль не должно быть пустым</p>");
        }
    });



    //rate
    $('.rate').fadeIn(2000, function () {
        $.ajax({
            url: "https://api.exchangerate.host/latests?base=AMD&symbols=USD,EUR,RUB",
            type: 'GET',  // http method
            success: function (data, status, xhr) {
                let rates = data.rates;
                //console.log(rates);
                jQuery.each(rates, function (key, val) {
                    let ratesInfo = 1 / val;
                    let currency;

                    if (key == 'RUB') {
                        currency = ratesInfo.toFixed(2);
                    } else {
                        currency = ratesInfo.toFixed();
                    }
                    $('.rate').append("1 " + key + " = " + currency + " AMD");
                    $('.rate').append('<br />');
                }
                )
            },
            error: function (xhr, type) {
                alert(xhr.statusText);
            }
        });
    });

});


