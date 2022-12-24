import axios from "axios";

export const authClient = axios.create({
    baseURL: import.meta.env.VITE_APP_API_URL,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

// authClient.interceptors.response.use(
//     (response) => response,
//     (error) => {
//         if (error.response && [401, 419].includes(error.response.status)) {
//             console.info("[401, 419]: User not authorized, login failed with API");
//         }
//         return Promise.reject(error);
//     }
// );

export default {
    async login(payload) {
        await authClient.get("/sanctum/csrf-cookie");
        return authClient.post("/login", payload);
    },
    logout() {
        return authClient.post("/logout");
    },
    getAuthUser() {
        return authClient.get("/api/user");
    },
    async registerUser(payload) {
        await authClient.get("/sanctum/csrf-cookie");
        return authClient.post("/register", payload);
    }

}
