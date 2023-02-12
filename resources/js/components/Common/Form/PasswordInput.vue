<template>
    <div class="relative">
        <Input id="password"
               :value="modelValue"
               @update:modelValue="$emit('update:modelValue', $event)"
               autocomplete="new-password"
               class="block mt-1 w-full"
               name="password"
               placeholder="Password"
               :item="item"
               :errors="errors"
               required
               :type="passwordVisible ? 'text' : 'password'"/>
        <button class="absolute top-1/2 -translate-y-1/2 right-4 h-6 w-6 block p-0" @click="passwordVisible = !passwordVisible"
                type="button"
                title="Toggle showing the password">
            <font-awesome-icon :icon="passwordVisible ? 'eye-slash' : 'eye'" class="h-full w-full text-gray-500"/>
        </button>
    </div>
</template>

<script>
import Input from "@/components/Common/Form/Input.vue";

export default {
    name: "PasswordInput",
    components: {Input},

    data() {
        return {
            passwordVisible: false,
        }
    },

    props: ['modelValue', 'item', 'errors']
}
</script>

<style scoped>

</style>
