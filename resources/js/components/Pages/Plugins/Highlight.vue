<template>
    <div class="mb-6 col-span-12 lg:col-span-6 flex flex-row align-items-center">
        <Icon :src="image"/>
        <div class="ml-4 flex flex-col justify-center content-center">
            <MutedText v-if="title" :margin-top="false">{{title}}</MutedText>
            <div class="text-base">{{description}}</div>
        </div>
    </div>
</template>

<script>
import Icon from "@/components/Common/Icon/Icon.vue";
import MutedText from "@/components/Common/MutedText.vue";

export default {
    name: "Highlight",
    components: {MutedText, Icon},

    props: {
        image: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: false,
        },
        description: {
            type: String,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
