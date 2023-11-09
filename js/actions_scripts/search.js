$(document).ready(function () {

    // page search
    let searchFormPage = document.querySelector(".search_form_page"),
        pageInput = searchFormPage.querySelector("input");

    searchFormPage.onsubmit = (e) => {

        if (pageInput.value.length == 0) {
            e.preventDefault();
            document.querySelector(".error_txt_page").style.display = "block";
            document.querySelector(".search_from_page__text").style.border = "1px solid red";
            document.querySelector(".error_txt_page").innerHTML = "Cannot be empty ";
        }

        if (pageInput.value.length < 2 && pageInput.value.length != 0) {
            e.preventDefault();
            document.querySelector(".error_txt_page").style.display = "block";
            document.querySelector(".search_from_page__text").style.border = "1px solid red";
            document.querySelector(".error_txt_page").innerHTML = "Minimum symbol 3";
        }
        pageInput.onkeyup = () => {
            if (pageInput.value.length > 2) {
                document.querySelector(".search_from_page__text").style.border = "1px solid lightgray";
                document.querySelector(".error_txt_page").style.display = "none";
            }
        }

    };

});