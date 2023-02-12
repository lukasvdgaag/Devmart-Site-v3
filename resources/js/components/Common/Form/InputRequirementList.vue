<template>
    <ul class="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400 mt-1">
        <template v-for="(item, index) in values">
            <li class="flex items-center gap-2" v-if="item.show"    >
                <font-awesome-icon v-if="item.met" icon="circle-check" class="text-green-500 dark:text-green-400"/>
                <font-awesome-icon v-else icon="circle-check" class="text-gray-400"/>

                {{ item.text }}
            </li>
        </template>
    </ul>
</template>

<script>
export default {
    name: "InputRequirementList",

    created() {
        this.requirements.forEach(requirement => {
            if (requirement.startsWith('min:')) {
                this.min = parseInt(requirement.replace('min:', ''));
                this.values.min.text = this.values.min.text.replace('{min}', this.min);
                this.values.min.show = true;
            } else if (requirement.startsWith('max:')) {
                this.max = parseInt(requirement.replace('max:', ''));
                this.values.max.text = this.values.max.text.replace('{max}', this.max);
                this.values.max.show = true;
            } else if (requirement.startsWith('regex:')) {
                if (this.type !== 'username') return; // If the type is already set to username, don't override it

                this.regex = new RegExp(requirement.replace('regex:', ''));
                this.values.regex.text = this.values.regex.text.replace('{regex}', this.regex);
                this.values.regex.show = true;
            } else if (requirement.startsWith('type:')) {
                this.type = requirement.replace('type:', '');
                if (this.type === 'username') {
                    this.regex = new RegExp('^[a-zA-Z0-9_-]+$');
                    this.values.regex.text = 'Only alphanumeric characters (a-z, 0-9, _, -)';
                    this.values.regex.show = true;
                }
            }
        });

        this.updateValues();
    },

    data() {
        return {
            min: 0,
            max: undefined,
            regex: undefined,
            type: undefined,
            values: {
                min: {
                    text: 'At least {min} characters',
                    met: false,
                    show: false,
                },
                max: {
                    text: 'Maximum {max} characters',
                    met: false,
                    show: false,
                },
                regex: {
                    text: 'Must match the following regex: {regex}',
                    met: false,
                    show: false,
                },
            }
        }
    },

    watch: {
        value() {
            this.updateValues();
        }
    },

    methods: {
        updateValues() {
            this.values.min.met = !this.min || this.value.length >= this.min;
            this.values.max.met = !this.max || (this.value.length > 0 && this.value.length <= this.max);
            this.values.regex.met = !this.regex || this.regex.test(this.value);
        },
        metAllRequirements() {
            if (this.min && !this.values.min.met) return false;
            if (this.max && !this.values.max.met) return false;
            return !this.regex || this.values.regex.met;
        }
    },

    props: {
        requirements: {
            type: Array,
            required: true
        },
        value: {
            type: String,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
