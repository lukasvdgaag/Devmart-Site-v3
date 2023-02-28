<template>
    <p class="flex justify-center">
        <img src="https://www.gcnt.net/inc/img/spigot/additions/git-header.png" alt="AdditionsPlus Logo">
    </p>

    <h1>AdditionsPlus API</h1>
    <p>
        The official API for AdditionsPlus: Everything your Minecraft server needs, in one plugin.<br>Create custom commands, menus, items, scoreboards,
        tablists, (animated) nametags, and much more!
    </p>
    <p>
        <a href="https://github.com/lukasvdgaag/AdditionsPlus-API/wiki">Please check out the wiki here.</a>
    </p>
    <p>
        <em>Created by <a href="https://www.gcnt.net/" class="static">Devmart</a>.</em>
    </p>

    <h2>Maven Dependency</h2>
    <p>
        <img src="https://img.shields.io/nexus/gcnt/net.gcnt/additionsplus?server=https%3A%2F%2Fnexuslite.gcnt.net&amp;label=Latest%20version"
             alt="APIversionImg">
        <br>
        The AdditionsPlus API is hosted on our customly made NexusLite Maven Repository Manager, which can be found <a
        href="https://nexuslite.gcnt.net/">here</a>. The latest version of the API is listed above.
    </p>

    <h3 id="repository">Repository</h3>
    <CodeHighlightBlock class="mt-2" language="XML" code="<dependency>
    <groupId>net.gcnt</groupId>
    <artifactId>additionsplus</artifactId>
    <version>{VERSION}</version>
    <scope>provided</scope>
</dependency>"/>

    <h3 id="dependency">Dependency</h3>
    <CodeHighlightBlock class="mt-2" language="XML" code="<dependency>
    <groupId>net.gcnt</groupId>
    <artifactId>additionsplus</artifactId>
    <version>{VERSION}</version>
    <scope>provided</scope>
</dependency>"/>

    <h2 id="quick-links">Quick Links</h2>

    <Quicklinks>
        <WikiQuicklink title="Plugin page" url="/plugins/1"/>
        <WikiQuicklink title="List of actions" :url="{name: 'wiki.additions.actions'}"/>
        <WikiQuicklink title="Discord Webhook Messenger" url="https://additions.gcnt.net/index.php" external/>
    </Quicklinks>

</template>

<script>
import CodeHighlightBlock from "@/components/Pages/Paste/CodeHighlightBlock.vue";
import WikiQuicklink from "@/components/Pages/Wiki/WikiQuicklink.vue";
import Quicklinks from "@/components/Common/Quicklinks.vue";

export default {
    name: "ApApiHome",
    components: {Quicklinks, WikiQuicklink, CodeHighlightBlock}
}
</script>

<style scoped>

</style>
