import axios from "axios";
import User from "@/models/rest/User";
import Paste from "@/models/rest/paste/Paste";
import PasteListResponse from "@/models/rest/response/PasteListResponse";

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
    async findUsersByUsername(username) {
        try {
            const res = await client.get(`/search`, {
                params: {
                    username
                }
            });
            return res.data.users;
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
    },
    /**
     * Get all pastes for a user
     * @param id
     * @param query
     * @param page
     * @returns {Promise<PasteListResponse>}
     */
    async fetchUserPastesById(id, query = '', page = 1) {
        const res = await client.get(`/${id}/pastes`, {
            params: {
                query,
                page,
            }
        });

        let pastes = [];
        for (const paste of res.data.pastes) {
            pastes.push(Paste.fromJson(paste));
        }
        return new PasteListResponse(res.data.total, res.data.currentPage, res.data.pages, pastes);
    }
}
