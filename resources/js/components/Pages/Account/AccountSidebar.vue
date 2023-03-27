<template>
    <Sidebar :links="links"/>
</template>

<script>
import Sidebar from "@/components/Common/SidebarLinks.vue";
import SidebarItem from "@/models/SidebarItem";

export default {
    name: "AccountSidebar",
    components: {Sidebar},
    props: {
        active: String
    },
    computed: {
        links() {
            return [
                new SidebarItem({name: 'account', query: this.query}, 'fa-house-user', 'Account Details'),
                new SidebarItem({name: 'accountBilling', query: this.query}, 'fa-money-bill-transfer', 'Billing'),
                new SidebarItem({name: 'accountSales', query: this.query}, 'fa-square-poll-vertical', 'Sales'),
                new SidebarItem({name: 'accountPastes', query: this.query}, 'fa-solid fa-paste', 'Your Pastes'),
            ];
        },
        query() {
            const param = this.$route.query.user;
            if (!param) return {};

            return {user: param};
        }
    }
}
</script>

<style scoped>

</style>
