document.addEventListener("DOMContentLoaded", () => {
    const menuBtn = document.getElementById("menu-btn");
    const menuClose = document.getElementById("menu-close");
    const menuLateral = document.getElementById("menu-lateral");
    const overlay = document.getElementById("overlay");

    // Abrir menú
    menuBtn.addEventListener("click", () => {
        menuLateral.classList.add("active");
        overlay.classList.add("active");
    });

    // Cerrar menú
    menuClose.addEventListener("click", () => {
        menuLateral.classList.remove("active");
        overlay.classList.remove("active");
    });

    // Cerrar menú al hacer clic en el overlay
    overlay.addEventListener("click", () => {
        menuLateral.classList.remove("active");
        overlay.classList.remove("active");
    });
});
