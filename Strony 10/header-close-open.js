/* 
function closeNav() {   // Po odpaleniu strony ( <body onload="closeNav()"> ) wykonuje się ten skrypt
    document.getElementById("navigationClosed").style.display = "none";
    document.getElementById("navigationOpen").style.display = "block";              
    document.getElementById("sidenav").style.display = "none";
}; 
*/

function openNav() {    // Po naciśnięciu na przycisk pojawiają się linki
    document.getElementById("navigationClosed").style.display = "block";
    document.getElementById("navigationOpen").style.display = "none";
    document.getElementById("sidenav").style.display = "block";
};

function closedNav() {  // Po naciśnięciu na przycisku ponownie znikną wszystkie linki
    document.getElementById("navigationClosed").style.display = "none";
    document.getElementById("navigationOpen").style.display = "block";
    document.getElementById("sidenav").style.display = "none";
};

