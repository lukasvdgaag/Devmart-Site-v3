<template>
    <div role="status" class="animate-pulse w-full" v-if="!paste">
        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-1/2 mb-2"></div>
        <div class="h-5 bg-gray-300 rounded-full dark:bg-gray-600 w-60 mb-6"></div>

        <div class="h-40 bg-gray-200 rounded dark:bg-gray-700 w-full "></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div v-else class="flex flex-col w-full">
        <h2>{{ paste.title }}</h2>
        <Stats>
            <Stat>By {{ paste.creator_username ?? 'anonymous' }}</Stat>
            <Stat>{{ formattedUpdatedAt }}</Stat>
            <Stat v-if="paste.style && paste.style !== 'none'">{{ paste.style == 'less' ? 'Error' : paste.style }}</Stat>
        </Stats>

        <div class="mt-4 mb-6">
            <div class="flex gap-2 justify-end font-semibold">
                <PasteActionLink :href="`/paste/${pasteId}/download`" icon="fa-solid fa-download" target="_blank" text="Download" smallIcon/>
                <PasteActionLink :href="`/paste/${pasteId}/raw`" icon="fa-solid fa-align-left" text="Raw" smallIcon/>
                <PasteActionLink :href="`/paste/${pasteId}/edit`" icon="fa-solid fa-pen" text="Edit" smallIcon highlighted/>

                <button class="primary w-fit px-4 py-2 rounded-md align-center gap-2 hidden lg:flex" @click="share">
                    <font-awesome-icon icon="fa-solid fa-share-nodes"/>
                    Share
                </button>
            </div>

            <CodeHighlightBlock v-if="paste.content"
                                :language="paste.style"
                                :code="paste.content ?? ''"
                                class="mt-2"
            />
        </div>

        <StickyFooter class="lg:hidden">
            <button class="primary w-full p-2 mt-0 rounded-md flex align-center gap-2" @click="share">
                <font-awesome-icon icon="fa-solid fa-share-nodes"/>
                Share
            </button>
        </StickyFooter>
    </div>
</template>

<script>
import PastesRepository from "@/services/PastesRepository";
import Stats from "@/components/Common/Stats.vue";
import Stat from "@/components/Common/Stat.vue";
import DateService from "@/services/DateService";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import CodeHighlightBlock from "@/components/Pages/Paste/CodeHighlightBlock.vue";
import PasteActionLink from "@/components/Pages/Paste/PasteActionLink.vue";

export default {
    name: "PasteInformationPage",
    components: {PasteActionLink, CodeHighlightBlock, StickyFooter, Stat, Stats},

    data() {
        return {
            /**
             * @type {Paste}
             */
            paste: null,
        }
    },

    computed: {
        formattedUpdatedAt() {

            if (!this.paste?.updated_at) return null;
            return DateService.formatDateRelatively(this.paste?.updated_at, true);
        }
    },

    created() {
        this.fetchPaste();
    },

    methods: {
        async fetchPaste() {
            try {
                this.paste = await PastesRepository.fetchPaste(this.pasteId);
            } catch (e) {
                this.$router.push({name: 'not-found'});
            }
        },
        share() {
            // copy the url to the clipboard
            navigator.clipboard.writeText(window.location.href);
        }
    },

    props: {
        pasteId: {
            type: String,
            required: true,
        }
    }
}
</script>

<style>
.hljs {
    @apply !bg-gray-100 rounded-lg;
}
</style>
