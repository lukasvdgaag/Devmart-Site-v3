<template>
    <svg v-if="percentage >= 0" class="icon small stroke-green-400 text-green-400" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
        <rect class="stroke-green-400 text-green-400" fill="none" height="256" width="256"/>
        <polyline class="stroke-green-400 text-green-400" fill="none" points="232.002 56 136.002 152 96.002 112 24.002 184" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="24"/>
        <polyline class="stroke-green-400 text-green-400" fill="none" points="232.002 120 232.002 56 168.002 56" stroke="#000" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="24"/>
    </svg>
    <svg v-else class="icon small stroke-red-400 text-red-400" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
        <rect class="stroke-red-400 text-red-400" fill="none" height="256" width="256"/>
        <polyline class="stroke-red-400 text-red-400" fill="none" points="232.002 200 136.002 104 96.002 144 24.002 72" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="24"/>
        <polyline class="stroke-red-400 text-red-400" fill="none" points="232.002 136 232.002 200 168.002 200" stroke="#000" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="24"/>
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
