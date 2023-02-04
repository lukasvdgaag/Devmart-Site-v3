<template>
    <Input :value="$slots.default()[0].children" disabled class="w-fit mt-1" />
</template>

<script>
import Input from "@/components/Common/Input.vue";

export default {
    name: "DisabledFormText",
    components: {Input}
}
</script>
