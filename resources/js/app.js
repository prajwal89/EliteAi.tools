import './bootstrap';

// main navbar open close behavior
document.addEventListener('DOMContentLoaded', function () {
    // open
    const burger = document.querySelectorAll('.navbar-burger');
    const menu = document.querySelectorAll('.navbar-menu');

    if (burger.length && menu.length) {
        for (var i = 0; i < burger.length; i++) {
            burger[i].addEventListener('click', function () {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }

    // close
    const close = document.querySelectorAll('.navbar-close');
    const backdrop = document.querySelectorAll('.navbar-backdrop');

    if (close.length) {
        for (var i = 0; i < close.length; i++) {
            close[i].addEventListener('click', function () {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }


    if (backdrop.length) {
        for (var i = 0; i < backdrop.length; i++) {
            backdrop[i].addEventListener('click', function () {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }
});


//*on scroll down behaviour
var toTopButton = document.getElementById("to-top-button");

var prevScrollpos = window.pageYOffset;
window.onscroll = function () {

    //auto hide navbar
    var currentScrollPos = window.pageYOffset;
    // console.log(currentScrollPos)
    if (currentScrollPos > 300) {

        // do not hide on user pages
        var currentUrl = window.location.href;
        var regex = /\/u\//; // Regular expression pattern
        if (regex.test(currentUrl)) {
            return;
        }


        if (prevScrollpos > currentScrollPos) {
            document.getElementById("main_navbar").style.top = "0";
        } else {
            document.getElementById("main_navbar").style.top = "-58px";
        }
    }
    prevScrollpos = currentScrollPos;

    //back to top button
    if (toTopButton) {
        if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
            toTopButton.classList.remove("hidden");
        } else {
            toTopButton.classList.add("hidden");
        }

        window.goToTop = function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
}
