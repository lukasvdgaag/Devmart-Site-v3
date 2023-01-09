<template>
    <dl class="w-full relative grid grid-cols-12">
        <dt class="table-cell align-top col-span-12 lg:col-span-3 text-left lg:text-right font-bold lg:font-normal lg:text-md pt-4 lg:p-4 lg:pb-2 bg-transparent lg:bg-gray-75">
            {{ label }}
        </dt>
        <dd class="table-cell align-top col-span-12 lg:col-span-9 py-2 pr-0 lg:pr-4">
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
