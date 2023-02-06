<template>
    <div class="flex flex-col align-center relative top-[-80px] mb-[-80px] p-[6.25rem_1.5rem_4rem] bg-primary w-full h-full left-0 min-h-[14rem] max-h-[16rem]">
        <div class="flex flex-col align-center">
            <div class="d-grid h-full">
                <div class="text-white col-span-12 text-center flex flex-col gap-2 align-center h-full">
                    <h1 class="text-white">{{ title }}</h1>
                    <p v-if="subtitle">{{ subtitle }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "HeaderBackground",

    props: {
        title: {
            type: String,
            required: true
        },
        subtitle: {
            type: String,
            required: false
        }
    }
}
</script>

<style scoped>

</style>
