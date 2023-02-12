<template>
    <div class="overflow-x-auto overflow-y-hidden bg-gray-100 rounded-lg">
        <table>
            <tbody>
            <tr v-for="(line, index) in codeLines" :id="`L${index}`" :key="index">
                <td class="code-line text-gray-400 text-right whitespace-nowrap select-none w-1 min-w-12">
                    {{ index + 1 }}
                </td>
                <td class="table-cell overflow-visible text-gray-900 dark:text-gray-200 whitespace-pre break-all" v-html="line">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import highlighter from "highlight.js";

export default {
    name: "CodeHighlightBlock",

    created() {
        this.highlight();
    },

    methods: {
        highlight() {
            let value;
            if (this.language === "none") {
                this.value = this.code;
            } else if (this.language) {
                value = highlighter.highlight(this.code, {language: this.language}).value;
            } else {
                value = highlighter.highlightAuto(this.code, this.allowedLanguages).value;
            }

            this.codeLines = value.split("\n");
            // replaces all empty lines with a &nbsp; character.
            this.codeLines = this.codeLines.map((e) => {
                if (e === "") return "\n";
                return e;
            })
        }
    },

    data() {
        return {
            highlightedCode: "",
            codeLines: [],
            allowedLanguages: [
                'javascript', 'java', 'yaml', 'less', 'json', 'html', 'css', 'php', 'xml'
            ],
        }
    },

    props: {
        code: {
            type: String,
            required: true,
        },
        language: {
            type: [String, null],
            required: true,
        },
    },
}
</script>

<style scoped>
td {
    @apply p-0 px-2 text-base leading-6 font-mono relative;
}

tr {
    @apply box-border border-spacing-0 border-collapse !border-t-0;
}

tr:first-child td {
    @apply pt-3
}

tr:last-child td {
    @apply pb-3
}
</style>
