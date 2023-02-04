import Paste from "@/models/rest/paste/Paste";
import PasteListResponse from "@/models/rest/response/PasteListResponse";

export const client = axios.create({
    baseURL: import.meta.env.VITE_APP_API_URL + "/api/paste",
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
        const res = await client.get("/", {
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
            const res = await client.post("/", body);

            return Paste.fromJson(res.data);
        } catch (e) {
            throw e;
        }
    }

}
