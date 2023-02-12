<template>
    <div class="w-full flex flex-col items-center m-0 p-0">
        <Navbar :background="true"/>

        <div class="h-screen w-full minus-header flex flex-col align-center gap-3">
            <div class="d-grid grid-cols-12 pt-5 pb-5">
                <div class="col-span-12 text-center flex flex-col align-center">
                    <div>
                        <h1>404 - Page Not Found</h1>
                        <MutedText class="text-md">We were unable to find the page you requested.</MutedText>

                        <router-link :to="{name:'home'}" class="block w-fit button !mt-4 primary p-2 plain text-xl">Back to Home</router-link>
                    </div>

                    <img :src="imageUrl" class="rounded-md mt-5 max-h-[250px]"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import MutedText from "@/components/Common/MutedText.vue";

export default {
    name: "PageNotFound",
    components: {MutedText, Navbar},

    mounted() {
        this.imageUrl = this.imageOptions[Math.floor(Math.random() * this.imageOptions.length)];
    },

    data() {
        return {
            imageUrl: '',
            imageOptions: [
                '/assets/img/404-1.gif',
                '/assets/img/404-2.gif',
                '/assets/img/404-3.gif',
                '/assets/img/404-4.gif',
                '/assets/img/404-5.gif',
            ]
        }
    },
}
</script>

<style scoped>

</style>
