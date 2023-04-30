<template>
    <div :class="[background ? [background, 'text-white'] : 'bg-gray-100 dark:bg-gray-800']"
         class="flex flex-row items-center rounded-md py-1.5 px-2 w-fit font-roboto text-xs">
        <font-awesome-icon v-if="icon" :icon="icon" class="mr-2"/>
        <span class="tracking-wider leading-none" :class="{'uppercase': uppercase, 'font-black': bold}" v-html="label"></span>
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
        },
        uppercase: {
            type: Boolean,
            default: true,
        },
        bold: {
            type: Boolean,
            default: true,
        }
    }
}
</script>
