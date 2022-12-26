<template>
    <svg v-if="percentage >= 0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="icon small stroke-green-400 text-green-400">
        <rect width="256" height="256" fill="none" class="stroke-green-400 text-green-400"/>
        <polyline fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="24" points="232.002 56 136.002 152 96.002 112 24.002 184" class="stroke-green-400 text-green-400"/>
        <polyline fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="24" points="232.002 120 232.002 56 168.002 56" class="stroke-green-400 text-green-400"/>
    </svg>
    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="icon small stroke-red-400 text-red-400">
        <rect width="256" height="256" fill="none" class="stroke-red-400 text-red-400"/>
        <polyline fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="24" points="232.002 200 136.002 104 96.002 144 24.002 72" class="stroke-red-400 text-red-400"/>
        <polyline fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="24" points="232.002 136 232.002 200 168.002 200" class="stroke-red-400 text-red-400"/>
    </svg>
</template>

<script>
export default {
    name: "GraphIcon",

    props: {
        percentage: {
            type: Number,
            required: true,
        }
    }
}
</script>
