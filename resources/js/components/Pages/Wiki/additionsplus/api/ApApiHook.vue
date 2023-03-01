<template>
    <h1>Hook into AdditionsPlus</h1>
    <hr>

    <h2>Overview</h2>
    <p>
        This page is about using the AdditionsPlus API in your own plugin, to either implement the Additions actions in your own plugin, add your own actions or
        events, or work with other features of this plugin.</p>
    <p>
        Please note, that the examples on this page are only available for AdditionsPlus 2.7.0 as the plugin, and thus the API, was fully remastered in 2.7.0.
    </p>

    <h2>First steps</h2>
    <p>
        Before you can actually make use of the AdditionsPlus API, you must first import it into your project.
    </p>

    <h3>Importing with Maven</h3>
    <p>
        To import AdditionsPlus, simply add the following code to your <strong>pom.xml</strong> file.
        Replace
        <i-code>{VERSION}</i-code>
        with the version listed at the top of this page.
    </p>

    <CodeHighlightBlock language="XML" code="<repositories>
    <repository>
        <id>gcnt</id>
        <url>https://nexuslite.gcnt.net/repos/gcnt/</url>
    </repository>
</repositories>

<dependencies>
    <dependency>
        <groupId>net.gcnt</groupId>
        <artifactId>additionsplus</artifactId>
        <version>{VERSION}</version>
        <scope>provided</scope>
    </dependency>
</dependencies>"/>

    <h3>Importing with Gradle</h3>
    <p>
        To import AdditionsPlus, simply add the following code to your <strong>build.gradle</strong> file.
        Replace
        <i-code>{VERSION}</i-code>
        with the version listed at the top of this page.
    </p>

    <CodeHighlightBlock language="Groovy" code="repositories {
    maven {
        url = 'https://nexuslite.gcnt.net/repos/gcnt/'
    }
}

dependencies {
    compileOnly 'net.gcnt:additionsplus:{VERSION}'
}"/>

    <h2>Set AdditionsPlus as (soft-)depend</h2>
    <p>
        The next step is adding AdditionsPlus as a (soft-)dependency in your
        <i-code>plugin.yml</i-code>
        file, located in the root of your plugin. Whether you add it as a
        dependency or a soft dependency is on you, depending on if it is optional or not. Not adding AdditionsPlus to the (soft-)depends will most likely cause
        issues since AdditionsPlus is loaded as one of the last plugins, after world loading.
        <br>
        <em>Please note that the Bukkit resource name is
            <i-code>Additions</i-code>
            and not AdditionsPlus!</em>
    </p>

    <h3>Example soft dependency</h3>
    <CodeHighlightBlock language="YAML" code="name: ExamplePlugin
version: 1.0.0
author: author
main: your.main.class.Here

softdepend: [Additions] # Used when your plugin can run without AdditionsPlus."/>

    <h3>Example hard dependency</h3>
    <CodeHighlightBlock language="YAML" code="name: ExamplePlugin
version: 1.0.0
author: author
main: your.main.class.Here

depend: [Additions] # Used when your plugin requires AdditionsPlus to work."/>

    <h2>Getting the API class</h2>
    <p>
        If you want to access any API methods or classes, you first need to get the main AdditionsPlus plugin class called
        <i-code>AdditionsPlugin</i-code>
        . In order to get this
        class, you need to get the JavaPlugin class from Bukkit. For efficiency, you can store this in a variable in your main class. Please do a sanity check
        first to see if the plugin is actually enabled before working with the API.
    </p>
    <p>The code below shows how to get the plugin class:</p>

    <CodeHighlightBlock language="java" code="AdditionsPlugin additions = (AdditionsPlugin) Bukkit.getPluginManager().getPlugin(&quot;Additions&quot;);
AdditionsAPI additionsAPI = additions.getAPI();"/>

    <p>From here on, you can access every public class that AdditionsPlus has to offer, including adding your own actions, events, and much more.</p>

</template>

<script>
import ICode from "@/components/Common/ICode.vue";
import CodeHighlightBlock from "@/components/Pages/Paste/CodeHighlightBlock.vue";

export default {
    name: "ApApiHook",
    components: {CodeHighlightBlock, ICode}
}
</script>

<style scoped>

</style>
