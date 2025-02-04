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
        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            try {
                const response = await fetch("../configBD/api.php?endpoint=login", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ username, password }),
                });

                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();

                if (data.token) {
                    localStorage.setItem("authToken", data.token);
                    localStorage.setItem("username", username);
                    window.location.href = "index.html";
                } else {
                    alert("Credenciales incorrectas");
                }
            } catch (error) {
                console.error("Error en login:", error);
                alert("Error al iniciar sesión. Intenta de nuevo.");
            }
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

    verificarAuth();
});
