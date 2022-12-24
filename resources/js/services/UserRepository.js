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
    }
}
