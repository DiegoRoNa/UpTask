const mobileMenubtn = document.querySelector('#mobile-menu');
const cerrarMenubtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');


if (mobileMenubtn) {
    mobileMenubtn.addEventListener('click', function(){
        sidebar.classList.add('mostrar');
    });
}

if (cerrarMenubtn) {
    cerrarMenubtn.addEventListener('click', function(){
        sidebar.classList.add('ocultar');

        setTimeout(() => {
            sidebar.classList.remove('mostrar');
            sidebar.classList.remove('ocultar');
        }, 500);
    });
}

//ELIMINAR LA CLASE DE MOSTRAR EN PANTALLAS GRANDES
const anchoPantalla = document.body.clientWidth;

window.addEventListener('resize', function(){
    const anchoPantalla = document.body.clientWidth;
    if (anchoPantalla >= 768) {
        sidebar.classList.remove('mostrar');
    }
});