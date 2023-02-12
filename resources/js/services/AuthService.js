import axios from "axios";

export const authClient = axios.create({
    baseURL: import.meta.env.VITE_APP_API_URL,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

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
