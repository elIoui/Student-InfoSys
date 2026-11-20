const navLinks = document.querySelectorAll('.sidebar a');
const currentPage = window.location.pathname.split("/").pop();

navLinks.forEach(link => {
    if(link.getAttribute('href') === currentPage){
        link.classList.add('active');
    } else {
        link.classList.remove('active');
    }
});
