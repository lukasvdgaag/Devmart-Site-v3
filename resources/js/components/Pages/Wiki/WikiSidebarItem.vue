<template>
    <li class="w-full">
        <div class="text-sm tracking-wide font-medium transition cursor-pointer select-none" :class="[isChild && !hasChildren ? 'capitalize' : 'uppercase leading-8']">
            <router-link v-if="!hasChildren && !route?.redirect"
                         :to="{name: route.name}"
                         :exact="true"
                         active-class=""
                         exact-active-class="active"
                         data-drawer-hide="drawer-navigation"
                         aria-controls="drawer-navigation"
                         class="block"
                         :class="[isChild ? 'child' : 'parent']"
            >{{ routeName }}
            </router-link>
            <div v-if="hasChildren" class="flex gap-2 items-center" @click="expanded = !expanded">
                <div class="w-full parent" :class="{active: active}">{{ routeName }}</div>
                <div class="mr-2">
                    <font-awesome-icon :rotation="expanded ? 90 : null"
                                       class="transition text-xs text-gray-600"
                                       icon="fa-chevron-right"
                    />
                </div>
            </div>
        </div>

        <ul v-if="hasChildren && expanded" class="pl-4 my-1">
            <WikiSidebarItem v-for="child in children" :route="child" class="p-0" is-child :full-path="route.path.concat('/', child.path)"
                             :key="route.path.concat(child.path)"/>
        </ul>
    </li>
</template>

<script>
import StringService from "@/services/StringService";

export default {
    name: "WikiSidebarItem",

    created() {
        if (this.active) this.expanded = true;
    },

    data() {
        return {
            expanded: false
        }
    },

    computed: {
        routeName() {
            return StringService.getWikiSidebarItemName(this.route);
        },
        children() {
            return this.route?.children ?? [];
        },
        hasChildren() {
            return this.children.length > 0;
        },
        active() {
            return this.$route.matched.some(route => route.path === "/wiki/" + this.fullPath);
        }
    },

    props: {
        route: {
            type: Object,
            required: true
        },
        isChild: {
            type: Boolean,
            default: false
        },
        fullPath: {
            type: String,
            default: ""
        }
    }
}
</script>

<style scoped>
.child, .parent {
    @apply font-roboto text-gray-600 dark:text-gray-300;
}
.child:hover, .parent:hover {
    @apply text-gray-800 dark:text-gray-100;
}

.child {
    @apply block border-l-2 pl-2 py-1 dark:border-gray-600;
}

.active {
    @apply text-black dark:text-gray-100 font-semibold;
}

.parent.active {
    @apply font-semibold;
}

.child.active {
    @apply border-l-gray-300 dark:border-gray-500;
}
</style>
