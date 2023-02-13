import axios from "axios";

export const client = axios.create({
    baseURL: import.meta.env.VITE_APP_API_URL + "/api/users",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

export default {
    async fetchUserById(id) {
        return await client.get(`/${id}`);
    },
    async updateUserById(id, payload) {
        return await client.put(`/${id}`, JSON.stringify(payload));
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
