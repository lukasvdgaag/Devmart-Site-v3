<template>
    <div class="flex flex-row items-center rounded-md py-1 px-2 w-fit font-roboto text-xs" :class="[background ? [background, 'text-white'] : 'bg-gray-100']">
        <font-awesome-icon v-if="icon" :icon="icon" class="mr-2 mt-[-2px]"/>
        <span class="font-black uppercase tracking-wider">{{ label}}</span>
    </div>
</template>

<script>
export default {
    name: "PluginLabel",

    props: {
        label: {
            type: String,
            required: true,
        },
        icon: {
            type: String,
            required: false,
        },
        background: {
            type: String,
            default: null
        }
    }
}
</script>

<style scoped>

</style>
