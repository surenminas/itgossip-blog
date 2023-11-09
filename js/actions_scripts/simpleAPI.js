//date display 
function displayDate(k) {
    let d = k * 1000;
    const date = new Date(d);

    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const formattedDate = date.toLocaleDateString('en-GB', options);

    return formattedDate;

}

//date display 2
function displayDate2(k) {
    let d = k * 1000;
    const date = new Date(d);

    let month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    const Y = date.getFullYear();
    const M = date.getMonth();
    const D = date.getDate();

    let result = D + ' ' + month[M] + ', ' + Y;

    return result;

}



// get API last posts from ajax
$('#API_form').submit(function (e) {
    e.preventDefault();
    var set_limit = $('#set_limit').val();
    var type = $('#type').val();

    let apiInput = document.querySelector("#set_limit");
        
    console.log(apiInput.value);    

    if (set_limit > 30) {
        document.querySelector("#set_limit").style.border = "1px solid red";

    } else {
        $.ajax({
            url: "simple-api",
            type: 'GET',  // http method
            dataType: "json",
            data: { limit: set_limit, type: type },
            success: function (data, status, xhr) {
                $('.api_show').empty();

                
                let postBox = document.querySelector('.api_show');

                for (let val of data) {

                    let postElement = document.createElement("div");

                    let inner = document.createElement("DIV");
                    // inner.classList.add('inner');

                    if (val.type == 'photo') {
                        postElement.classList.add('col-lg-4', 'post_card_api');

                        postElement.innerHTML = `
                        <div class="inner">
                            <div class="thumbnail">
                                <img src="uploads/photos/${val.img}" class="rounded" alt="posts">
                            </div>
                        </div>
                        `;
                        postBox.appendChild(postElement);
                    } else {

                        postElement.classList.add('col-lg-6', 'post_card_api');

                        postElement.innerHTML = `
                        <div class="inner">
                            <div class="thumbnail">
                                <a href="single?page=${val.id}"><img src="uploads/posts_img/${val.img}" class="rounded" alt="posts"></a>
                            </div>
                            <div class="card_block_body">
                                <ul class="rainbow_meta_list">
                                    <li><span>By: </span><a href="author?author_id=${val.id}">${val.username}</a></li>
                                    <li>, ${displayDate2(val.date)}</li>
                                </ul>
                            <h3 class="card_block__title"><a href="single?page=${val.id}">${val.title}</a></h3>
                            <p class="card_block__text">${val.description}</p>
                            </div>
                        </div>
                        `;
                        postBox.appendChild(postElement);
                    }
                }
            },
            error: function (xhr) {
                // alert('error API get');
                alert(xhr.statusText);
            }
        });
    }
    apiInput.onkeyup = () => {
        if (apiInput.value < 31) {
            document.querySelector("#set_limit").style.border = "1px solid gray";           
        }
    }


});