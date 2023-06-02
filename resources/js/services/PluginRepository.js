import axios from "axios";
import Plugin from "../models/rest/plugin/Plugin";
import PluginListResponse from "../models/rest/plugin/PluginListResponse";
import PluginPermissions from "../models/rest/plugin/PluginPermissions";
import PluginUpdate from "@/models/rest/plugin/PluginUpdate";
import PluginUpdatesResponse from "@/models/rest/plugin/PluginUpdatesResponse";
import PluginPurchase from "@/models/rest/plugin/PluginPurchase";
import PluginPurchasesResponse from "@/models/rest/plugin/PluginPurchasesResponse";

export const client = axios.create({
    baseURL: "/api/plugins",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

const loadParams = (user, query, from, to, page, perPage, sum, compareFrom = null, compareTo = null) => {
    const params = {};
    if (query) params.query = query;
    if (user) params.user = user;
    if (from) params.from = from.toISOString();
    if (to) params.to = to.toISOString();
    if (page) params.page = page;
    if (perPage) params.perPage = perPage;
    if (sum != null) params.sum = sum ? 1 : 0;
    if (compareFrom) params.compareFrom = compareFrom.toISOString();
    if (compareTo) params.compareTo = compareTo.toISOString();
    return params;
}

export default {

    /**
     * @param {number|null} user
     * @param {string|null} query
     * @param {number} page
     * @param {Date|null} from
     * @param {Date|null} to
     * @param {number|null} perPage
     * @param {boolean|null} sum
     * @returns {Promise<AxiosResponse<any>>}
     */
    async fetchSales(user = null, query = null, page = 1, from = null, to = null, perPage = null, sum = null) {
        return await client.get("/sales", {
            params: loadParams(user, query, from, to, page, perPage, sum)
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
            params: loadParams(user, null, from, to, null, null, true, compareFrom, compareTo)
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
            params: loadParams(user, query, from, to, records, null, null)
        })
    },

    /**
     * Fetch a list of plugins, ordered by last updated.
     * @param filter
     * @param query
     * @param page
     * @param perPage
     * @returns {Promise<PluginListResponse>}
     * @throws {Error} If the route could or an error occurred
     */
    async fetchPlugins(filter = "all", query = "", page = 1, perPage = 6) {
        const res = await axios.get(client.defaults.baseURL, {
            params: {
                filter,
                query,
                page,
                perPage
            }
        });
        const data = res.data;

        let plugins = [];
        for (let plugin of data.plugins) {
            plugins.push(Plugin.fromJson(plugin));
        }
        return new PluginListResponse(data.total, data.currentPage, data.pages, plugins);
    },

    /**
     * Fetches a list of updates for a specific plugin.
     * @param pluginId Id of the plugin
     * @param page Page number
     * @param perPage Number of items per page
     * @returns {Promise<PluginUpdatesResponse>} The requested versions.
     * @throws {Error} If the plugin could not be found or the user does not have permission to view it
     */
    async fetchPluginUpdates(pluginId, page = 1, perPage = 10) {
        const res = await client.get(`/${pluginId}/updates`, {
            params: {
                page,
                perPage,
            }
        });
        const data = res.data;

        let updates = [];
        for (let update of res.data.updates) {
            updates.push(PluginUpdate.fromJson(update));
        }
        return new PluginUpdatesResponse(data.total, data.currentPage, data.pages, updates);
    },

    /**
     * Fetches a list of recent purchases for a specific plugin.
     * @param pluginId Id of the plugin
     * @param page Page number
     * @param perPage Number of items per page
     * @param query Search query for email or username
     * @param from Start date
     * @param to End date
     * @returns {Promise<PluginPurchasesResponse>}
     */
    async fetchPluginPurchases(pluginId, page = 1, perPage = 15, query = undefined, from = undefined, to = undefined) {
        const res = await client.get(`/${pluginId}/transactions`, {
            params: {
                page,
                perPage,
                query,
                from,
                to
            }
        });
        const data = res.data;

        let purchases = [];
        for (let purchase of res.data.transactions) {
            purchases.push(PluginPurchase.fromJson(purchase));
        }
        return new PluginPurchasesResponse(data.total, data.currentPage, data.pages, purchases);
    },

    /**
     * Fetches a specific plugin update.
     * @param {number|string} updateId Id of the update
     * @returns {Promise<PluginUpdate>}
     */
    async fetchPluginUpdate(updateId) {
        const res = await client.get(`/updates/${updateId}`);
        return PluginUpdate.fromJson(res.data);
    },

    /**
     * Fetches a plugin by its ID
     * @param {number|string} id Id of the plugin
     * @param {boolean} featuresField Whether to include the features field
     * @param {boolean} saleField Whether to include the sale field
     * @param {boolean} totalDownloadsField Whether to include the totalDownloads field
     * @param {boolean} authorNameField Whether to include the authorName field
     * @returns {Promise<Plugin>} The requested plugin.
     * @throws {Error} If the plugin could not be found or the user does not have permission to view it
     */
    async fetchPlugin(id, featuresField = false, saleField = true, totalDownloadsField = true, authorNameField = true) {
        const res = await client.get(`/${id}`, {
            params: {
                featuresField,
                saleField,
                totalDownloadsField,
                authorNameField
            }
        });
        return Plugin.fromJson(res.data);
    },

    /**
     * Get the user's permissions for the requested plugin.
     * @param {number|string} pluginId Id of the plugin
     * @returns {Promise<PluginPermissions>}
     * @throws {Error} If the plugin could not be found or the user does not have permission to view it
     */
    async fetchPluginPermissions(pluginId) {
        const res = await client.get(`/${pluginId}/permissions`);
        return PluginPermissions.fromJson(res.data);
    },

    async fetchUpcomingSales(pluginId) {
        return await client.get(`/${pluginId}/sales`);
    },

    /**
     * Edit the plugin information.
     * @param {string|number} id
     * @param {Object} data
     * @returns {Promise<AxiosResponse<any>>}
     */
    async editPlugin(id, data) {
        return await client.put(`/${id}`, data);
    },

    async updatePlugin(id, formData) {
        return await client.post(`/${id}/update`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
    },

    async grantPluginAccess(pluginId, userId) {
        return await client.post(`/${pluginId}/access/${userId}`);
    },

    async revokePluginAccess(pluginId, userId) {
        return await client.delete(`/${pluginId}/access/${userId}`);
    }

}
