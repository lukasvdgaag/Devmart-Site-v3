import axios from "axios";

export const authClient = axios.create({
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

export default {
    async login(payload) {
        await authClient.get("/sanctum/csrf-cookie");
        return authClient.post("/api/auth/login", payload);
    },
    logout() {
        return authClient.post("/api/auth/logout");
    },
    getAuthUser() {
        return authClient.get("/api/user");
    },
    async registerUser(payload, discordAuthToken) {
        await authClient.get("/sanctum/csrf-cookie");
        return authClient.post(`/api/auth/register${discordAuthToken ? `?discord_auth_token=${discordAuthToken}` : ''}`, payload);
    }

}
