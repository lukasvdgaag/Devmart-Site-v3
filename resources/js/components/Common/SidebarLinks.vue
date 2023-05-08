<template>
    <Sidebar :margin="margin">
        <SidebarItem v-for="item in links" :item="item" :links="links" />
    </Sidebar>
</template>

<script>
import Sidebar from "@/components/Common/Sidebar.vue";
import SidebarItem from "@/components/Common/SidebarItem.vue";

export default {
    name: "SidebarLinks",
    components: {SidebarItem, Sidebar},

    props: {
        margin: {
            type: Boolean,
            default: false,
        },
        links: {
            type: Array,
            default: () => [],
        },
    }
}
</script>

<style scoped>

</style>
