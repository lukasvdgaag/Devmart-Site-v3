<template>
    <component v-if="url"
               :is="external ? 'a' : 'router-link'"
               :to="!external ? url : ''"
               :href="external ? url : ''"
               :target="external ? '_blank' : ''"
               class="quicklink col-span-1"
               :class="this.class">
        <div class="flex flex-col">
            <div class="flex items-center gap-3">
                <font-awesome-icon :icon="icon" class="text-xl"/>
                <div class="font-semibold text-lg">{{ label }}</div>
            </div>
            <p class="mt-1" v-if="description">{{ description }}</p>
            <a class="static mt-2" v-if="linkText">{{ linkText }}</a>
        </div>
    </component>

    <div v-else class="quicklink col-span-4 plain grid-half-small grid-full-smallest" :class="this.class">
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
        class: {
            type: String,
            required: false,
            default: ""
        },
        label: {
            type: String,
            required: true
        },
        description: {
            type: String,
            required: false
        },
        linkText: {
            type: String,
            required: false
        },
        icon: {
            type: String,
            required: false
        },
        external: {
            type: Boolean,
            required: false,
            default: false,
        },
        url: {
            type: [String, Object],
            required: false,
        }
    }
}
</script>

<style scoped>

</style>
