<template>
    <h1>Use actions in your plugins</h1>
    <hr>

    <h2>Overview</h2>
    <p>
        On this page, we will be explaining how you what AdditionsPlus actions are, what types there are, how you can
        integrate execute actions from your plugin, and how to add your own.
        Please make sure that you have set up the dependency correctly.
        You can read how
        <router-link :to="{name: 'wiki.additions.api.usage'}">here</router-link>.
    </p>

    <h2>What is an action?</h2>
    <p>
        In AdditionsPlus, an action is a simple, yet powerful, way to execute a command or a series of commands
        based on specific conditions that you can set. Actions are very useful for creating custom commands or events, such as
        a custom death event, or a custom win event. We are currently distinguishing between five types of actions:
    </p>
    <p>
        For a list of all actions, please see the <router-link :to="{name: 'wiki.additions.actions'}">actions page</router-link>.
    </p>

    <h3>Action types</h3>
    <ol>
        <li>
            <b><i-code>INNER</i-code></b>: Action that can have inline arguments. This is often used for condition checking.
            <ul>
                <li>Example:
                    <em><i-code>[delay=50]</i-code></em>
                </li>
            </ul>
        </li>
        <li>
            <b><i-code>OUTER</i-code></b>: Action that has an opening and closing tag with arguments in between.
            <ul>
                <li>Example:
                    <em><i-code>[math](5 * 5) / (1 / 9)[/math]</i-code></em>
                </li>
            </ul>
        </li>
        <li>
            <b><i-code>STANDALONE</i-code></b>: Action that does not necessarily require a value to work, and will work without any arguments
            specified.
            <ul>
                <li>Example:
                    <em><i-code>[close]</i-code></em>
                </li>
            </ul>
        </li>
        <li>
            <b><i-code>NORMAL</i-code></b>: Action that requires a value to work, and cannot have any inline arguments.
            <ul>
                <li>Example:
                    <em><i-code>[message]&aThis is a chat message</i-code></em>
                </li>
            </ul>
        </li>
        <li>
            <b><i-code>COMBINED</i-code></b>: Action that has to be combined with a <i>NORMAL</i> or <i>INNER</i> action in order to work and does
            nothing on its own.
            This is often used for condition checking, but without the need for arguments.
            <ul>
                <li>Example:
                    <em><i-code>[return]</i-code> or <i-code>[everyone]</i-code></em>
                </li>
            </ul>
        </li>
    </ol>

    <p>
        Each action is assigned to one of these types and requires a <a href="https://hub.spigotmc.org/javadocs/bukkit/org/bukkit/plugin/java/JavaPlugin.html"
                                                                        target="_blank">JavaPlugin</a>, identifier,
        and a <a href="https://docs.oracle.com/javase/8/docs/api/java/util/function/Consumer.html" target="_blank">Java Consumer</a>.
        Think about what type of action suits your action best, because they are all handled a little differently.
        Currently, it is not possible to add custom condition-checking actions.
    </p>

    <h2>Perform a list of actions</h2>
    <p>
        In AdditionsPlus, we work with <a href="https://www.gcnt.net/javadocs/additionsplus/net/gcnt/additionsplus/api/actions/ActionSender.html"
                                          target="_blank">ActionSenders</a>.
        This is basically a wrapper class/bridge for console senders and players.
        All queued actions have a primary executor.
        This is often the 'owner' of the action, to whom the actions are targeted.
    </p>
    <p>
        Let's say you are making a game plugin, and you want to perform a list of win actions
        for the player that won the game. Below is a method that allows you to do this:
    </p>

    <CodeHighlightBlock language="java" code='public void performWinActions(Player player) {
    // Get the AdditionsPlus instance.
    AdditionsPlugin additions = (AdditionsPlugin) Bukkit.getPluginManager().getPlugin("Additions");
    ActionSender sender = additions.getAPI().getActionSender(player);

    // Gets the &quot;win-actions&quot; property from the config.yml file. Replace main with your main class.
    List<String> actions = main.getConfig().getStringList("win-actions");
    // Queue the actions.
    additions.getAPI().performActions(sender, actions);
}'/>

    <h2>Add a new action</h2>
    <p>
        <em>For this example, you need the AdditionsAPI class as explained <router-link :to="{name: 'wiki.additions.api.usage'}">here.</router-link></em>
    </p>

    <p>
        For this example, we will be registering the custom action when your plugin is being enabled.
        If you wish to use this exact code, please put it in your main class.
        Please note that this code will fail when you run it since we are using default actions in this example,
        which are already registered.
    </p>

    <CodeHighlightBlock language="java" code='@Override
public void onEnable() {
    // Get the AdditionsPlus instance.
    AdditionsPlus additions = (AdditionsPlus) Bukkit.getPluginManager().getPlugin("Additions");
    // Create the action using the API.
    // This sends the value of the action to the sender (player or console), with parsed colors. They can use [message] or [msg].
    AdditionsAction action = additions.getAPI().createCustomAction(this, "message", ActionType.NORMAL, queued -> {
        // Everything in here will be executed when the action is being called.
        queued.getSender().sendMessage(queued.getColoredValue());
    }, false, "msg");

    // Registering the newly created action.
    boolean registered = additions.getAPI().registerCustomAction(action);
    // Checking if it was actually registered. This returns false if an action with your identifier already exists.
    if (registered) Bukkit.getLogger().info("Successfully registered the AdditionsPlus action!");
    else Bukkit.getLogger().severe("Failed to register the AdditionsPlus action!");
}'/>

</template>

<script>
import CodeHighlightBlock from "@/components/Pages/Paste/CodeHighlightBlock.vue";
import ICode from "@/components/Common/ICode.vue";

export default {
    name: "ApApiUseActions",
    components: {ICode, CodeHighlightBlock}
}
</script>

<style scoped>

</style>
