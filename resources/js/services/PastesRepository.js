import Paste from "@/models/rest/paste/Paste";
import PasteListResponse from "@/models/rest/response/PasteListResponse";

export const client = axios.create({
    baseURL: "/api/paste",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

export default {

    /**
     * Fetches recent public pastes
     * @param page Page number
     * @param perPage Number of pastes per page
     * @returns {Promise<PasteListResponse>}
     */
    async fetchRecentPastes(page = 1, perPage = 8) {
        const res = await client.get(client.defaults.baseURL, {
            params: {page, perPage}
        });

        let pastes = [];
        for (const paste of res.data.pastes) {
            pastes.push(Paste.fromJson(paste));
        }
        return new PasteListResponse(res.data.total, res.data.currentPage, res.data.pages, pastes);
    },

    /**
     * Create a new paste.
     * @param {PasteCreateBody} body
     * @returns {Promise<Paste>}
     */
    async createPaste(body) {
        try {
            const res = await client.post(client.defaults.baseURL, body);

            return Paste.fromJson(res.data);
        } catch (e) {
            throw e;
        }
    },

    /**
     * Update an existing paste.
     * @param pasteId Paste ID
     * @param {PasteCreateBody} body Paste body
     * @returns {Promise<*>}
     */
    async updatePaste(pasteId, body) {
        try {
            const res = await client.put(`/${pasteId}`, body);
            return Paste.fromJson(res.data);
        } catch (e) {
            throw e;
        }
    },

    /**
     * Delete an existing paste
     * @param pasteId Paste ID
     * @returns {Promise<boolean>}
     */
    async deletePaste(pasteId) {
        try {
            const res = await client.delete(`/${pasteId}`);
            return res.status === 200;
        } catch (e) {
            throw e;
        }
    },

    /**
     * Fetches a paste by its name
     * @param {string} name Paste name
     * @returns {Promise<Paste>}
     */
    async fetchPaste(name) {
        const res = await client.get(`/${name}`);
        return Paste.fromJson(res.data);
    }

}
