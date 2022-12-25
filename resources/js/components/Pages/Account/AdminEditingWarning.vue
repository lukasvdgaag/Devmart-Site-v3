<template>
    <Alert type="warning" class="mb-4 text-black" icon="fa-circle-exclamation">
        <div>
            <div class="text-lg mb-2 font-bold">Hello Admin,</div>
            <div>
                You are viewing the account page of {{ username }}. Be aware of the details you edit and
                make sure that they are valid. Any changes made must be reported to the user concerned.
                <br>
                <router-link :to="{name:'admin'}" class="block !text-black mt-1 italic">
                    &#60; Click here to go back to the admin page.
                </router-link>
            </div>
        </div>
    </Alert>
</template>

<script>
import Alert from "@/components/Common/Alert.vue";

export default {
    name: "AdminEditingWarning",
    components: {Alert},

    props: {
        username: {
            type: String,
            required: true
        }
    },
}
</script>

<style scoped>

</style>
