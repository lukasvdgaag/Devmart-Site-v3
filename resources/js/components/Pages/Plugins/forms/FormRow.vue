<template>
    <dl class="w-full relative grid grid-cols-12 gap-4 group">
        <dt class="group-first:rounded-t-lg group-last:rounded-b-lg table-cell align-top col-span-12 lg:col-span-3 text-left lg:text-right font-bold lg:font-normal lg:text-md lg:pt-4 lg:p-4 lg:pb-2 bg-transparent lg:bg-gray-75">
            {{ label }}
        </dt>
        <dd class="table-cell align-top col-span-12 lg:col-span-9 pt-1 lg:pt-2 pb-2 pr-0">
            <slot/>
        </dd>
    </dl>
</template>

<script>
export default {
    name: "FormRow",
    props: {
        label: {
            type: String,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
