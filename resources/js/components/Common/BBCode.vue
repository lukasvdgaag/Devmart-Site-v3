<template>
    <div class="bbcode markdown" v-html="BBCodeParser.parse(source)">
    </div>
</template>

<script>
import BBCodeParser from "@/services/BBCodeParser";

export default {
    name: "BBCode",
    computed: {
        BBCodeParser() {
            return BBCodeParser
        }
    },

    props: {
        source: {
            type: String,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
