<template>
    <div class="sidebar-left sidebar-left-margin mb-2">
        <router-link :to="{name: 'accountHome', query: query}"
                     class="preview-filter-link flex flex-row align-items-center plain"
                     :class="{'active': active === 'home'}">
            <div class="preview-link-icon flex justify-content-center">
                <font-awesome-icon icon="fa-house-user"/>
            </div>
            <h3 class="preview-link-title">Account Details</h3>
        </router-link>
        <router-link :to="{name: 'accountBilling', query: query}"
                     class="preview-filter-link flex flex-row align-items-center plain"
                     :class="{'active': active === 'billing'}">
            <div class="preview-link-icon">
                <font-awesome-icon icon="fa-money-bill-transfer"/>
            </div>
            <h3 class="preview-link-title">Billing</h3>
        </router-link>
        <router-link :to="{name: 'accountSales', query: query}"
                     class="preview-filter-link flex flex-row align-items-center plain"
                     :class="{'active': active === 'sales'}">
            <div class="preview-link-icon">
                <font-awesome-icon icon="fa-money-bill-transfer"/>
            </div>
            <h3 class="preview-link-title">Sales</h3>
        </router-link>
        <router-link :to="{name: 'accountPastes', query: query}"
                     class="preview-filter-link flex flex-row align-items-center plain"
                     :class="{'active': active === 'pastes'}">
            <div class="preview-link-icon">
                <font-awesome-icon icon="fa-solid fa-paste"/>
            </div>
            <h3 class="preview-link-title">Your Pastes</h3>
        </router-link>
    </div>

</template>

<script>
export default {
    name: "AccountSidebar",
    props: {
        active: String
    },
    computed: {
        query() {
            const param = this.$route.query.user;
            if (!param) return {};

            return {
                user: param
            }
        }
    }
}
</script>

<style scoped>

</style>
