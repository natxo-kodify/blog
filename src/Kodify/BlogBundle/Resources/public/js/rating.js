/**
 * Created by Cristian on 21/09/2015.
 */
$(function () {
    function setStars(stars) {
        var star_icons = $(".stars i");
        star_icons.removeClass("fa-star").addClass("fa-star-o");
        for (var i = 0; i < stars; i++) {
            star_icons.eq(i).removeClass("fa-star-o").addClass("fa-star");
        }
    }

    var star_links = $(".stars a");
    star_links.on("mouseenter", function (e) {
        e.preventDefault();
        setStars($(this).data("star"));
    });

    star_links.on("mouseleave", function (e) {
        e.preventDefault();
        setStars($(".stars").data("default-stars"));
    });
    setStars($(".stars").data("default-stars"));
});
