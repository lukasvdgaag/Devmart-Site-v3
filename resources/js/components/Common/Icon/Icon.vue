<template>
    <div class="min-w-10 min-h-10 w-10 h-10 rounded-full bg-gray-75 text-gray-800 flex align-center hover:bg-gray-300">
        <img :src="src" alt="Highlight icon" class="w-5 rounded"/>
    </div>
</template>

<script>
export default {
    name: "Icon",

    props: {
        src: {
            type: String,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
