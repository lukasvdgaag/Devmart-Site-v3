import axios from "axios";
import Order from "@/models/rest/order/Order";

export const client = axios.create({
    baseURL: "/api/orders",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true
});

export default {
    async fetchOrderById(id) {
        if (id == null || id?.length !== 36) return null;

        try {
            const res = await client.get(`/${id}`);
            return Order.fromJson(res.data);
        } catch (e) {
            return null;
        }
    },
}
