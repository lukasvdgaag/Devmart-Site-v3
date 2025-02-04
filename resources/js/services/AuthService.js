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
        return authClient.post("/login", payload);
    },
    logout() {
        return authClient.post("/logout");
    },
    getAuthUser() {
        return authClient.get("/api/user");
    },
    async registerUser(payload, discordAuthToken) {
        await authClient.get("/sanctum/csrf-cookie");
        return authClient.post(`/register${discordAuthToken ? `?discord_auth_token=${discordAuthToken}` : ''}`, payload);
    }

}
