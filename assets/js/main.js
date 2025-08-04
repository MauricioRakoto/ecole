const menu = document.getElementById('menu')
const modalMenu = document.querySelector('.navbar.navbar-expand .menu .menu-modal')

function toggleModalMenu ()

{
    setTimeout(() => {
        modalMenu.classList.toggle('show')
    }, 1000)
}

menu.addEventListener('click', toggleModalMenu)