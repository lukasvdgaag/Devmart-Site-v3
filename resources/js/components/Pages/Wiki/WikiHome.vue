<template>
    <h1>Wiki Home</h1>
    <hr>
    <p>
        Welcome to the Home of the Devmart Wiki. This is the place to go to for any documentation related
        to Devmart services or projects. Get started by selecting a topic from the Table Of Contents
        or quickly head into a popular wiki with any of the links below.
    </p>

    <div class="grid grid-cols-3 mt-6 gap-5 row-gap-2">
        <router-link to="#" class="quicklink col-span-1">
            <div>AdditionsPlus Wiki</div>
            <font-awesome-icon icon="fa-arrow-right" class="text-lg"/>
        </router-link>
        <router-link to="#" class="quicklink col-span-1">
            <div>SkyWarsReloaded Wiki</div>
            <font-awesome-icon icon="fa-arrow-right" class="text-lg"/>
        </router-link>
        <router-link to="#" class="quicklink col-span-1">
            <div>StreakRewards Wiki</div>
            <font-awesome-icon icon="fa-arrow-right" class="text-lg"/>
        </router-link>
    </div>

    <h2 class="mt-6">Quick Links</h2>
    <div class="grid grid-cols-3 mt-2 gap-5 row-gap-2">
        <a href="/github" class="quicklink mt-2 gh col-span-1">
            <div class="flex flex-col">
                <div class="flex items-center gap-3">
                    <font-awesome-icon icon="fa-brands fa-github" class="text-xl"/>
                    <div class="font-semibold text-lg">GitHub</div>
                </div>
                <p class="mt-1">Explore our GitHub and view our source codes.</p>
                <a class="static mt-2">Take me there!</a>
            </div>
        </a>
        <a href="/spigot" class="quicklink mt-2 sp col-span-1">
            <div class="flex flex-col">
                <div class="flex items-center gap-3">
                    <font-awesome-icon icon="faucet" class="text-xl"/>
                    <div class="font-semibold text-lg">SpigotMC</div>
                </div>
                <p class="mt-1">Visit our SpigotMC page to view our resources!</p>
                <a class="static mt-2">On my way!</a>
            </div>
        </a>
        <a href="/discord" class="quicklink mt-2 dc col-span-1">
            <div class="flex flex-col">
                <div class="flex items-center gap-3">
                    <font-awesome-icon icon="fa-brands fa-discord" class="text-xl"/>
                    <div class="font-semibold text-lg">Discord</div>
                </div>
                <p class="mt-1">Join our Discord server to stay up to date and get quick support!</p>
                <a class="static mt-2">Count me in!</a>
            </div>
        </a>
    </div>

</template>

<script>


import QuickLink from "@/components/Common/QuickLink.vue";

export default {
    name: "WikiHome",
    components: {QuickLink}
}
</script>

<style scoped>
.quicklink.sp:hover {
    background-image: url("https://static.spigotmc.org/img/spigot.png");
    background-color: #d8811f;
    background-position-y: 100%;
    background-position-x: 100%;
}

.quicklink.gh:hover {
    background-image: url("/assets/img/github-logo-colored.png");
    background-color: #000;
}

.quicklink.dc:hover {
    background-position-y: 100%;
    background-image: url("https://assets-global.website-files.com/6257adef93867e50d84d30e2/636e0a6ca814282eca7172c6_icon_clyde_white_RGB.svg");
    background-position: 105% 105%;
    background-repeat: no-repeat;
    background-size: 80px;
    background-color: #5865F2;
    fill: white;
}

.quicklink.dc:hover *, .quicklink.gh:hover *, .quicklink.sp:hover * {
    color: white !important;
    fill: white;
}
</style>
