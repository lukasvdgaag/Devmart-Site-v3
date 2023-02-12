<template>
    <div v-if="item in errors" class="text-red-400 text-md mt-1">
        {{ errors[item][0] }}
    </div>
</template>

<script>
export default {
    name: "ValidationError",

    props: {
        errors: {
            type: Object,
            required: true,
        },
        item: {
            type: String,
            required: true
        },
    }
}
</script>
