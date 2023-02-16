import axios from "axios";
import User from "@/models/rest/User";

export const client = axios.create({
    baseURL: import.meta.env.VITE_APP_API_URL + "/api/users",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

export default {
    async fetchUserById(id) {
        try {
            const res = await client.get(`/${id}`);
            return User.fromJson(res.data.user);
        } catch (e) {
            return null;
        }
    },
    async updateUserById(id, payload) {
        try {
            const res = await client.put(`/${id}`, JSON.stringify(payload));
            return User.fromJson(res.data);
        } catch (e) {
            throw e;
        }
    },
    async fetchUserPayPalById(id) {
        return await client.get(`/${id}/paypal`);
    },
    async updateUserPayPalById(id, payload) {
        return await client.put(`/${id}/paypal`, JSON.stringify(payload));
    },
    async fetchDiscordInformation(discordId) {
        return await client.get(import.meta.env.VITE_APP_API_URL + `/api/discord/user/${discordId}`);
    }
}
