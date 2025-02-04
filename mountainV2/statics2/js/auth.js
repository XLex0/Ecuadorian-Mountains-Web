document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const loginBtn = document.getElementById("login-btn");
    const logoutBtn = document.getElementById("logout-btn");
    const deleteUserBtn = document.getElementById("delete-user-btn");

    function getAuthToken() {
        return localStorage.getItem("authToken");
    }

    function verificarAuth() {
        const token = getAuthToken();
        if (token) {
            loginBtn.style.display = "none";
            logoutBtn.style.display = "block";
            if (deleteUserBtn) {
                deleteUserBtn.style.display = "block";
            }
        } else {
            loginBtn.style.display = "block";
            logoutBtn.style.display = "none";
            if (deleteUserBtn) {
                deleteUserBtn.style.display = "none";
            }
        }
    }

    // LOGIN
    if (loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            fetch("../configBD/api.php?endpoint=login", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.token) {
                    localStorage.setItem("authToken", data.token);
                    localStorage.setItem("username", username);
                    window.location.href = "index.html";
                } else {
                    alert("Credenciales incorrectas");
                }
            });
        });
    }

    // REGISTRO
    if (registerForm) {
        registerForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const username = document.getElementById("new-username").value;
            const password = document.getElementById("new-password").value;

            fetch("../configBD/api.php?endpoint=register", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Usuario registrado con éxito");
                    window.location.href = "login.html";
                } else {
                    alert("Error al registrar");
                }
            });
        });
    }

    // LOGOUT
    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            localStorage.removeItem("authToken");
            localStorage.removeItem("username");
            window.location.href = "index.html";
        });
    }

    // ELIMINAR USUARIO
    if (deleteUserBtn) {
        deleteUserBtn.addEventListener("click", () => {
            const username = localStorage.getItem("username");
            if (!username) {
                alert("No hay un usuario autenticado.");
                return;
            }

            if (!confirm("¿Estás seguro de que deseas eliminar tu cuenta? Esta acción es irreversible.")) {
                return;
            }

            fetch("../configBD/api.php?endpoint=deleteUser", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${getAuthToken()}`
                },
                body: JSON.stringify({ username }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Usuario eliminado correctamente.");
                    localStorage.removeItem("authToken");
                    localStorage.removeItem("username");
                    window.location.href = "index.html";
                } else {
                    alert("Error al eliminar usuario.");
                }
            });
        });
    }

    verificarAuth();
});
