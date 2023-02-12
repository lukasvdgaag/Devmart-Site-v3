<template>
    <div class="bbcode-editor-container flex flex-col">
        <div class="bbcode-editor-header flex flex-row">
            <div :class="{active: !showParsed}" class="item" @click="showParsed = false">Editor</div>
            <div :class="{active: showParsed}" class="item" @click="showParsed = true">Preview</div>
        </div>

        <BBCode v-if="showParsed" :source="parsedBBCode()" class="bbcode-preview"/>
        <Input v-else
               :is-textarea="true"
               :placeholder="placeholder"
               :required="required"
               :value="modelValue"
               class="bbcode-editor"
               @input="$emit('update:modelValue', $event.target.value)"/>
    </div>
</template>

<script>
import Input from "@/components/Common/Input.vue";
import BBCodeParser from "@/services/BBCodeParser";
import BBCode from "@/components/Common/BBCode.vue";

export default {
    name: "BBCodeEditor",
    components: {BBCode, Input},

    data() {
        return {
            showParsed: false
        }
    },

    methods: {
        parsedBBCode() {
            return BBCodeParser.parse(this.modelValue);
        }
    },

    props: {
        modelValue: {
            type: String,
            required: false,
            default: ''
        },
        required: {
            type: Boolean,
            default: false,
        },
        placeholder: {
            type: String,
            default: 'BBCode content'
        }
    }
}
</script>

<style scoped>

</style>
