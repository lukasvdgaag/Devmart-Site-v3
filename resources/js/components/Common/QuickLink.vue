<template>
    <div class="wiki-quicklink grid-4 plain grid-half-small grid-full-smallest">
        <div class="flex flex-col justify-between w-full h-full">
            <template v-if="icon">
                <div class="flex flex-col">
                    <div class="highlight-icon-container flex flex-col align-center">
                        <font-awesome-icon :icon="icon"/>
                    </div>
                    <MutedText>{{ label }}</MutedText>
                </div>
                <div class="flex flex-col align-content-center mt-2 mb-2 w-100 bold size-24">
                    <slot/>
                </div>
            </template>
            <template v-else>
                <MutedText>{{ label }}</MutedText>
                <slot/>
            </template>
        </div>
    </div>
</template>

<script>
import MutedText from "@/components/Common/MutedText.vue";

export default {
    name: "QuickLink",
    components: {MutedText},

    props: {
        label: {
            type: String,
            required: true
        },
        icon: {
            type: String,
            required: false
        },
    }
}
</script>

<style scoped>

</style>
