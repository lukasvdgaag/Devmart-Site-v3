<template>
    <h1>Scoreboards Setup</h1>
    <hr>

    <h2>About</h2>
    <p>
        AdditionsPlus brings global and per-world scoreboard customization to your Minecraft server.
        Each line supports animations, (PAPI) placeholders, color codes, and even HEX colors and
        Gradients on 1.16+. Servers that are running any version from 1.8 until 1.12 are bound to a
        maximum of 32 characters per line. 1.13+ servers can have unlimited line lengths.
        Scoreboards are still limited to a maximum of 16 lines.
    </p>

    <h2>Set up the config file</h2>
    <p>
        The scoreboards feature has its own section in the <i>config.yml</i> file,
        under the name <cite>scoreboards</cite>. Make sure that scoreboards are enabled by setting
        <i-code>enabled</i-code>
        to <cite>true</cite>.
        There are some more options that may be worth checking out:
    </p>

    <ul>
        <li>
            <i-code>remove_when_disabled</i-code>
            removes the scoreboard when the player
            toggles the scoreboard or when the player enters a world without a scoreboard.
        </li>
        <li>
            <i-code>update_interval</i-code>
            determines how often the scoreboard
            should be updated. This number is in ticks (1 second = 20 ticks).
        </li>
        <li>
            <i-code>display_main_scoreboard_when_no_per_world_found</i-code>
            determines whether AdditionsPlus should fall back to the main/default
            scoreboard when no scoreboard is found for the world the player is in.
        </li>
        <li>
            <i-code>perWorld</i-code>
            determines whether per-world mode is enabled.
        </li>
    </ul>

    <h2>Set up the scoreboard configuration file</h2>
    <p>
        The scoreboards.yml file has two sections:
        <i-code>scoreboard</i-code>
        and
        <i-code>worlds</i-code>
        .
        The <cite>scoreboard</cite> section is used as the main or default scoreboard
        and will be used as primary scoreboard across the server when enabled.
        The <cite>worlds</cite> section is used to specify scoreboards for specific worlds.
        This section consists of subsections that are named after the
        worlds that the scoreboard should be applied to.
    </p>


    <h3>Individual Scoreboard Settings</h3>
    <p>Each scoreboard consists of a couple of options:</p>
    <ul>
        <li>
            <i-code>enabled</i-code>
            determines whether this specific scoreboard should be
            enabled. Disabling it will either stop updating it or removing it from
            the screen depending on your config settings.
        </li>
        <li>
            <i-code>title</i-code>
            represents the title of the scoreboard.
        </li>
        <li>
            <i-code>lines</i-code>
            represents a list of lines for the scoreboard.
        </li>
    </ul>

    <h3>Per World Scoreboards</h3>
    <p>
        As previously described, each world has its own individual scoreboard settings under the
        <i-code>worlds</i-code>
        section in the <cite>scoreboards.yml</cite> file.
        In order for per-world scoreboards to work, the
        <i-code>perWorld</i-code>
        option needs to be enabled in the <cite>scoreboards</cite> section of the <cite>config.yml</cite>
        file.<br>To disable the scoreboard in a specific world, simply set the
        <i-code>enabled</i-code>
        option of the scoreboard to <cite>false</cite>.
    </p>
    <p>
        <router-link to="/paste/sLtfCzxZ" title="Go to the example per-world setup">
            An example setup for per-world scoreboards can be found here.
        </router-link>
    </p>

</template>

<script>
import ICode from "@/components/Common/ICode.vue";

export default {
    name: "APSetupScoreboards",
    components: {ICode}
}
</script>

<style scoped>

</style>
