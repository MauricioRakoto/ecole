<script src="./assets/js/main.js"></script>

<script>
$(document).ready(function() {
    let lastScrollTop = 0;

    $(window).on("scroll", function() {
        let currentScroll = $(this).scrollTop();

        if (currentScroll > lastScrollTop) {
            // Scroll vers le bas → on cache les éléments
            $(".navbar a, .navbar img, .navbar h3, .navbar h4, .btn-menu, .menu-name").fadeOut(200);
        } else {
            // Scroll vers le haut → on montre les éléments
            $(".navbar a, .navbar img, .navbar h3, .navbar h4, .btn-menu, .menu-name").fadeIn(200);
        }

        lastScrollTop = currentScroll;
    });
});
</script>
