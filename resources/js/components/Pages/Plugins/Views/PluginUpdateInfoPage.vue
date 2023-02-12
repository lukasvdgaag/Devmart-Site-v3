<template>
    <h2 class="my-2">Updates</h2>

    <div class="flex flex-col gap-2">
        <PluginUpdateInformation v-if="update" :permissions="permissions" :update="update"/>
    </div>
</template>

<script>
import PluginRepository from "@/services/PluginRepository";
import PluginUpdateInformation from "@/components/Pages/Plugins/PluginUpdateInformation.vue";
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";

export default {
    name: "PluginUpdateInfoPage",
    components: {PluginUpdateInformation},

    created() {
        this.loadUpdate();
    },

    data() {
        return {
            update: null,
        }
    },

    methods: {
        async loadUpdate() {
            try {
                this.update = await PluginRepository.fetchPluginUpdate(this.updateId);

                if (!this.update || this.update.plugin != this.pluginId) {
                    this.$router.push({name: 'not-found'});
                }
            } catch (e) {
                this.$router.push({name: 'not-found'});
            }
        }
    },

    props: {
        updateId: {
            required: true,
        },
        pluginId: {
            required: true,
        },
        plugin: {
            type: Plugin,
            required: true,
        },
        permissions: {
            type: [PluginPermissions, null],
            required: true,
        },
    }
}
</script>

<style scoped>

</style>
