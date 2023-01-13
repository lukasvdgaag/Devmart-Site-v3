<template>
    <div class="rounded-md shadow-sm focus:ring focus:ring-opacity-50 w-full" multiple @click="$refs.catsInput.$el.focus()">
        <div class="inline">
            <div v-for="category in categories" class="bg-primary rounded-md py-1 px-1.5 text-white uppercase w-fit h-6 inline-flex break-keep my-0.5 mr-1 items-center font-medium text-sm">
                <span class="w-fit">{{category}}</span>
                <font-awesome-icon @click="e => deleteCategory(e,category)" icon="fa-circle-xmark" class="h-full w-full ml-1 pointer"/>
            </div>
        </div>

        <Input v-model="currentCategory"
               @keydown="e => handleInput(e)"
               class="border-none shadow-none text-sm p-1"
               maxlength="20"
               type="text"
               ref="catsInput"
               name="categories"
        />
    </div>
    <MutedText class="mt-1" :margin-top="false">{{description}}</MutedText>
</template>

<script>
import Input from "@/components/Common/Input.vue";
import MutedText from "@/components/Common/MutedText.vue";

export default {
    name: "CategoryInput",
    components: {MutedText, Input},
    emits: ['update:modelValue'],

    data() {
        return {
            currentCategory: "",
            categories: this.modelValue ?? [],
        }
    },

    computed: {
      description() {
          if (this.categories.length >= this.maxCategories) {
              return "You have reached the maximum number of categories!"
          }

          return `You have added ${this.categories.length}/${this.maxCategories} categories.`
      }
    },

    methods: {
        deleteCategory(e, category) {
            if (this.currentCategory.length > 0 || this.categories.length === 0) return;

            e.preventDefault();
            if (category === undefined) this.currentCategory = this.categories.pop();
            else {
                // remove item with value of category from the categories array
                this.categories = this.categories.filter(c => c !== category);
            }

            this.$emit('update:modelValue', this.categories);
        },
        addCategory(e) {
            if (this.categories.length >= this.maxCategories) return;

            e.preventDefault();
            const value = this.currentCategory.trim();
            if (value.length !== 0 && !this.categories.includes(value)) {
                this.categories.push(value);
                this.currentCategory = "";
                this.$emit('update:modelValue', this.categories);
            }
        },
        handleInput(e) {
            if (e.keyCode === 8) {
                // backspace
                this.deleteCategory(e);
            } else if (e.keyCode === 32 || e.keyCode === 13) {
                if (this.categories.length >= this.maxCategories) {
                    e.preventDefault();
                    return;
                }
                // enter OR space
                this.addCategory(e);
            } else if (e.keyCode < 65 || e.keyCode > 90 || this.categories.length >= this.maxCategories) {
                e.preventDefault();
            }
        },
    },

    props: {
        modelValue: {
            type: Array,
            required: false,
        },
        maxCategories: {
            type: Number,
            required: false,
            default: 6,
        },
    }
}
</script>

<style scoped>

</style>
