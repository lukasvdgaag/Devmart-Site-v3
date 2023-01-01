<template>
    <div class="bbcode markdown">
        {{bbcode}}
    </div>
</template>

<script>
import core from '@bbob/core'
import * as html from '@bbob/html'
import { isStringNode, isTagNode } from '@bbob/plugin-helper';

export default {
    name: "BBCode",

    computed: {
        bbcode() {
            if (this.$slots.default) {
                const source = this.$slots.default.reduce((acc, vnode) => acc + vnode.text, '');
                return this.render(createElement('span'), source, this.plugins, this.options);
            }
        }
    },

    methods: {
        toAST(source, plugins = [], options = {}) {
            return core(plugins).process(source, {
                ...options,
                render: (input) => html.render(input, {stripTags: true});
            }).tree;
        },
        isContentEmpty(content) {
            return !content || content.length === 0;
        },
        tagToVueNode(createElement, node, index) {
            const { class: className, style, ...domProps } = node.attrs || {};

            return createElement(
                node.tag,
                {
                    key: index,
                    class: className,
                    style,
                    domProps,
                },
                this.isContentEmpty(node.content) ? null : this.renderToVueNodes(createElement, node.content),
            );
        },
        renderToVueNodes(createElement, nodes) {
            return [].concat(nodes).reduce((arr, node, index) => {
                if (isTagNode(node)) {
                    arr.push(this.tagToVueNode(createElement, node, index));
                } else if (isStringNode(node)) {
                    arr.push(node);
                }

                return arr;
            }, []);
        },
        render(createElement, source, plugins, options) {
            return this.renderToVueNodes(createElement, this.toAST(source, plugins, options));
        }
    },

    props: {
        container: {
            type: String,
            default: 'span'
        },
        plugins: {
            type: Array,
        },
        options: {
            type: Object,
        }
    }
}
</script>

<style scoped>

</style>
