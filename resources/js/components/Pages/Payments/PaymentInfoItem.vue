<template>
    <div class="flex-1">
        <div class="text-sm font-semibold">{{title}}</div>
        <div class="text-xs dark:text-gray-400">{{ value }}</div>
    </div>
</template>

<script>
export default {
    name: "PaymentInfoItem",

    props: {
        title: {
            type: String,
            required: true,
        },
        value: {
            type: String,
            required: true,
        },
    }
}
</script>

<style scoped>

</style>
