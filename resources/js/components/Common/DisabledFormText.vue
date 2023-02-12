<template>
    <Input :value="$slots.default()[0].children" class="w-fit mt-1" disabled/>
</template>

<script>
import Input from "@/components/Common/Input.vue";

export default {
    name: "DisabledFormText",
    components: {Input}
}
</script>
