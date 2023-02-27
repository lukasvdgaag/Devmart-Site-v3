<template>
    <div class="quicklink col-span-4 plain grid-half-small grid-full-smallest">
        <div class="flex flex-col justify-between w-full h-full">
            <template v-if="icon">
                <div class="flex flex-col">
                    <Icon :src="icon" isFontAwesome/>
                    <MutedText>{{ label }}</MutedText>
                </div>
                <div class="flex flex-col content-center mt-2 mb-2 w-full font-bold text-2xl">
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
import Icon from "@/components/Common/Icon/Icon.vue";

export default {
    name: "QuickLink",
    components: {Icon, MutedText},

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
