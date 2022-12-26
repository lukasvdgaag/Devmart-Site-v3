import axios from "axios";

export const client = axios.create({
    baseURL: import.meta.env.VITE_APP_API_URL + "/api/plugins",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

const loadParams = (user, query, from, to, records, sum, compareFrom = null, compareTo = null) => {
    const params = {};
    if (query) params.query = query;
    if (user) params.user = user;
    if (from) params.from = from.toISOString();
    if (to) params.to = to.toISOString();
    if (records) params.records = records;
    if (sum != null) params.sum = sum ? 1 : 0;
    if (compareFrom) params.compareFrom = compareFrom.toISOString();
    if (compareTo) params.compareTo = compareTo.toISOString();
    return params;
}

export default {

    /**
     * @param {Number|null} user
     * @param {String|null} query
     * @param {Date|null} from
     * @param {Date|null} to
     * @param {Number|null} records
     * @param {Boolean} sum
     * @returns {Promise<AxiosResponse<any>>}
     */
    async fetchSales(user = null, query = null, from = null, to = null, records = null, sum = null) {
        return await client.get("/sales", {
            params: loadParams(user, query, from, to, records, sum)
        })
    },

    /**
     * @param {Number|null} user
     * @param {Date|null} from
     * @param {Date|null} to
     * @param {Date|null}compareFrom
     * @param {Date|null}compareTo
     * @returns {Promise<AxiosResponse<any>>}
     */
    async fetchSalesSum(user = null, from = null, to = null, compareFrom = null, compareTo = null) {
        return await client.get("/sales", {
            params: loadParams(user, null, from, to, null, true, compareFrom, compareTo)
        })
    },

    /**
     * @param {Number|null} user
     * @param {String|null} query
     * @param {Date|null} from
     * @param {Date|null} to
     * @param {Number|null} records
     * @returns {Promise<AxiosResponse<any>>}
     */
    async fetchDailySales(user = null, query = null, from = null, to = null, records = null) {
        return await client.get("/sales/daily", {
            params: loadParams(user, query, from, to, records, false)
        })
    }


}
