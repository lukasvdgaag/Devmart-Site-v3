<template>
    <router-view></router-view>
</template>

<script>
    import {DateService} from "@/services/DateService";

    export default {
        name: "App",
        provide() {
            return {
                dateService: new DateService(),
            }
        }
    }
</script>
