
// $(document).ready(function() {
//     var clean_timer;

//     $(window).resize(function(){
//         clearTimeout(clean_timer);
//         clean_timer = setTimeout(function () {
//             updateBoxCaption();
//         }, 200);
//     })
// });

// $(".big-thumb").one("load", function() {
//     var container = $(this).closest('.thumb-container-big');

//     $(".boxcaption", container).stop().css('top', (container.height() - $(".thumb-info", container).height() - 4) + "px");
//     $(this).removeClass('big-thumb');
// }).each( function() {
//     if(this.complete) {
//         $(this).trigger('load');
//     }
// });

// function updateBoxCaption() {
//     $(".thumb-container-big").each(function(index) {
//         $(".boxcaption", this).stop().css('top', ($(this).height() - $(".thumb-info", this).height() - 4) + "px");
//     });
// };

//Show/hide additional info for the wallpaper
$(".post_card_photo").on("mouseenter",function() {
    // var top_position = ($(this).height(30));
    // console.log(top_position);


    // $(".boxcaption", this).stop().animate({ top: top_position },{queue:false,duration:160});
    // $('.tags-info').addClass(".d-block").show();
}).on("mouseleave", ".thumb-container-big", function() {
    var top_position = ($(this).height() - $(".thumb-info", this).height() - 4) + "px";

    $(".boxcaption", this).stop().animate({ top: top_position },{queue:false,duration:160});
    $('.tags-info', this).hide();
});

