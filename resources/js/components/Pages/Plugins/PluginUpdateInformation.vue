<template>
    <div class="bg-gray-75 rounded-md px-4 py-3 border border-gray-200 mb-2 flex flex-col">
        <div class="flex justify-between gap-2">
            <div class="font-bold text-lg">
                <router-link :to="{name: 'plugin-update', params: {updateId: update.id}}" class="static">{{ update.title }}</router-link>
                <span class="font-normal text-gray-400 text-base lg:text-lg lg:ml-1 block lg:inline-block">
                    <span class="lg:hidden">Version: </span>
                    {{ update.effective_version }}</span>
            </div>
            <a v-if="permissions?.download" :href="`/plugins/${update.plugin}/download/${update.file_name}`" target="_blank"
               class="plain rounded-md bg-primary-400 hover:bg-primary flex-nowrap text-white h-fit py-2 lg:py-1 px-3 text-xl lg:text-base lg:hidden lg:px-2 break-keep flex gap-2 align-center mr-[-4px]">
                <font-awesome-icon icon="fa-solid fa-file-arrow-down"/>
            </a>
        </div>

        <p v-html="changelog" :class="{'text-ellipsis max-h-[120px] overflow-hidden break-words': !expanded}" />
        <button v-if="changelogLines.length > 5"
                class="bg-gray-200 px-2 py-1 mt-3 text-gray-800 lg:hidden flex gap-2 items-center w-fit"
                @click="expanded = !expanded">
            <font-awesome-icon icon="fa-chevron-up" :rotation="expanded ? 0 : 90" class="transition text-sm text-gray-600"></font-awesome-icon>

            {{ expanded ? 'Read less...' : 'Read more...'}}
        </button>

        <div class="flex justify-between mt-3 items-center">
            <div class="gap-3 hidden lg:flex ">
                <a v-if="permissions?.download" :href="`/plugins/${update.plugin}/download/${update.file_name}`" target="_blank"
                   class="plain rounded-md bg-primary-400 hover:bg-primary flex-nowrap text-white h-fit py-2 lg:py-1 px-3 text-xl lg:text-base lg:px-2 break-keep flex gap-2 align-center mr-[-4px]">
                    <font-awesome-icon icon="fa-solid fa-file-arrow-down"/>
                    <span class="break-keep whitespace-nowrap hidden lg:block">Download</span>
                </a>
                <button v-if="changelogLines.length > 5"
                        class="bg-gray-200 px-2 py-1 mt-0 text-gray-800 flex gap-2 items-center w-fit"
                        @click="expanded = !expanded">
                    <font-awesome-icon icon="fa-chevron-up" :rotation="expanded ? 0 : 90" class="transition text-sm text-gray-600"></font-awesome-icon>

                    {{ expanded ? 'Read less...' : 'Read more...'}}
                </button>
            </div>
            <Stats class="!mt-0">
                <Stat :dot="false" :small="true">Updated {{ DateService.formatDateRelatively(new Date(update.created_at), true) }}</Stat>
                <Stat :small="true">{{ update.downloads }} downloads</Stat>
            </Stats>
        </div>
    </div>
</template>

<script>
import Stats from "@/components/Common/Stats.vue";
import Stat from "@/components/Common/Stat.vue";
import PluginUpdate from "@/models/rest/PluginUpdate";
import DateService from "../../../services/DateService";
import PluginPermissions from "@/models/rest/PluginPermissions";

export default {
    name: "PluginUpdateInformation",
    computed: {
        DateService() {
            return DateService
        },
        changelog() {
            return this.changelogLines.join('<br>');
        }
    },
    components: {Stat, Stats},

    created() {
        this.loadChangelog();
    },

    data() {
        return {
            changelogLines: [],
            expanded: false,
        }
    },

    methods: {
        parseLine(line) {
            return line.replaceAll(/^((Fixed)|(Added)|(Removed)|(Improved)|(Upgraded))/gmi, '<span class="font-semibold">$1</span>');
        },
        loadChangelog() {
            this.changelogLines = this.update.changelog.split("<br>")
                .map(line => this.parseLine(line));
        }
    },

    props: {
        update: {
            type: PluginUpdate
        },
        permissions: {
            type: PluginPermissions
        }
    },
}
</script>

<style scoped>

</style>
