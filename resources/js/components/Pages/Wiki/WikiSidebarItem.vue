<template>
    <li class="w-full">
        <div class="text-sm tracking-wide font-medium transition cursor-pointer select-none" :class="[isChild ? 'capitalize' : 'uppercase leading-8']">
            <router-link v-if="!hasChildren"
                         :to="{name: route.name}"
                         :exact="true"
                         active-class=""
                         exact-active-class="active"
                         class="block"
                         :class="[isChild ? 'child' : 'parent']"
            >{{ routeName }}</router-link>
            <div v-else class="flex items-center" @click="expanded = !expanded">
                <div class="w-full parent" :class="{active: active}">{{ routeName }}</div>
                <font-awesome-icon :rotation="expanded ? 90 : null"
                                   class="transition text-xs text-gray-600"
                                   icon="fa-chevron-right"
                />
            </div>
        </div>

        <ul v-if="hasChildren && expanded" class="pb-2 pl-3 my-1">
            <WikiSidebarItem v-for="child in children" :route="child" class="p-0" is-child :key="route.path.concat(child.path)"/>
        </ul>
    </li>
</template>

<script>
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
            const name = this.route.path.replace('/', '').replace('-', ' ');
            return this.route.meta?.name ?? (name === '' ? 'Introduction' : name);
        },
        children() {
            return this.route?.children ?? [];
        },
        hasChildren() {
            return this.children.length > 0;
        },
        active() {
            return this.$route.matched?.some(route => route.path === this.route.path);
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
        }
    }
}
</script>

<style scoped>
    .child, .parent {
        @apply font-roboto;
    }
    .child {
        @apply block border-l border-l-4 pl-2 py-1 text-gray-600;
    }
    .parent {
        @apply text-gray-600;
    }
    .parent.active {
        @apply text-black font-semibold;
    }
    .child.active {
        @apply border-l-gray-300 text-black;
    }
</style>
