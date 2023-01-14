<template>
    <div class="w-full flex flex-col items-center m-0 p-0">
        <Navbar :background="true"/>

        <div class="d-grid !grid-cols-12 gap-0" v-if="initialized">
            <div class="titled-header col-span-12 text-center">
                <h1 class="page-title">Edit Plugin</h1>
                <p class="page-subtitle">{{ plugin.name }}</p>

                <div class="grid !grid-cols-12 gap-0">
                    <div class="col-span-12 lg:col-span-10 lg:col-start-2 rounded-xl py-9 md:px-5 text-left mt-2">
                        <PluginPreview :plugin="plugin"/>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-10 lg:col-start-2 rounded-xl pt-3 md:px-5 flex flex-col">
                <div class="text-base mb-2 font-bold">
                    <router-link :to="{name: 'plugin-overview', params: {pluginId: pluginId}}" class="static">Return to the plugin page.</router-link>
                </div>

                <form @submit.prevent="savePlugin">
                    <FormSection>
                        <FormRow label="Title">
                            <Input v-model="plugin.title"
                                   class="block w-full"
                                   name="title"
                                   placeholder="Plugin title"
                                   required/>
                        </FormRow>
                        <FormRow label="Short Description">
                            <Input v-model="plugin.description"
                                   class="block w-full"
                                   name="description"
                                   placeholder="Plugin description"
                                   required/>
                        </FormRow>
                        <FormRow label="Categories">
                            <CategoryInput v-model="categories"/>
                        </FormRow>
                    </FormSection>

                    <FormSection>
                        <FormRow label="Banner Image">
                            <img :src="plugin.banner_url" alt="Banner image" class="plugin-preview-banner"/>

                            <FileInput @upload="updateBannerPreview" accept=".jpg,.jpeg,.png,.webp,.svg,.avif"/>
                        </FormRow>
                        <FormRow label="Logo Image">
                            <img :src="plugin.logo_url" alt="Logo image" class="icon huge rounded-xl"/>

                            <FileInput @upload="updateLogoPreview" accept=".jpg,.jpeg,.png,.webp,.svg,.avif"/>
                        </FormRow>
                    </FormSection>

                    <FormSection>
                        <FormRow label="Price">
                            <Input v-model="plugin.price"
                                   class="block w-full"
                                   placeholder="Price"
                                   min="0"
                                   type="number"
                                   required/>
                            <MutedText>
                                The one-time price a user has to pay to gain access to this project.
                                <br>
                                Set to 0 to make this project free.
                            </MutedText>
                        </FormRow>

                        <FormRow v-if="plugin.price > 0" label="Sale">
                            <button v-if="!plugin?.sale"
                                    class="primary new-sale"
                                    type="button"
                                    @click="createNewSale()">New Sale
                            </button>
                            <div v-else>
                                <div class="flex flex-row">
                                    <div class="grid gap-0 md:gap-2 grid-cols-12 w-full">
                                        <Input class="col-span-2 w-full rounded-r-none md:rounded-r-md"
                                               v-model="plugin.sale.percentage"
                                               type="number"
                                               min="0"
                                               max="100"
                                               placeholder="%"
                                               required/>
                                        <Input class="col-span-5 w-full rounded-none md:rounded-md"
                                               v-model="plugin.sale.start_date"
                                               type="datetime-local"
                                               placeholder="Start date"
                                               required/>
                                        <Input class="col-span-5 w-full rounded-l-none md:rounded-l-md"
                                               v-model="plugin.sale.end_date"
                                               type="datetime-local"
                                               placeholder="End date"
                                        />
                                    </div>
                                    <button class="text-lg h-12 w-12 flex align-center mt-0" type="button" @click.prevent="plugin.sale = null">
                                        <font-awesome-icon icon="fa-circle-xmark" class="text-red-500 w-full h-full"/>
                                    </button>
                                </div>
                                <MutedText>
                                    This sale will result in the new total price being <span
                                    class="font-bold">{{ StringService.formatMoney((plugin.price / 100) * (100 - plugin.sale.percentage), false) }}</span>
                                    with {{ plugin.sale.percentage }}% off.<br>
                                    <span class="italic">Leave the end date empty to make this sale last indefinitely. All dates are in CET.</span>
                                </MutedText>
                            </div>
                        </FormRow>

                        <FormRow v-if="plugin.price > 0" label="Custom">
                            <SwitchInput class="mt-2" v-model="plugin.custom" :checked="plugin.custom"/>
                            <MutedText>
                                When enabled, this project will be marked as custom. It will not be indexed by
                                search engines, updates will not be announced to the Discord, and only people with
                                direct access can view the project.
                            </MutedText>
                        </FormRow>
                    </FormSection>
                    <FormSection>
                        <FormRow label="Supported Versions">
                            <ul class="columns-2 md:columns-3 lg:columns-4 gap-x-4">
                                <li v-for="(selected, version) in minecraftVersions"
                                    :key="version"
                                    class="list-none">
                                    <label class="flex items-center mb-1">
                                        <Input type="checkbox"
                                               @change="updateMinecraftVersion(version)"
                                               :checked="minecraftVersions[version]"
                                               class="mr-2"/>
                                        {{ version }}
                                    </label>
                                </li>
                            </ul>
                        </FormRow>
                        <FormRow label="(Soft) Dependencies">
                            <Input class="block w-full"
                                   v-model="plugin.dependencies"
                                   placeholder="Enter any (soft) dependencies"/>
                        </FormRow>
                    </FormSection>

                    <FormSection>
                        <FormRow label="Spigot id">
                            <Input class="block mt-1 w-full"
                                   min="0"
                                   type="number"
                                   placeholder="67706"
                                   v-model="plugin.spigot_link"/>
                            <MutedText>
                                If this project has an associated SpigotMC resource page, please enter the numeric id of
                                the page. You can find it at the end of the resource page's URL, after the dot.
                                <br>
                                <span class="italic">E.g: https://www.spigotmc.org/resources/additions-plus.67706. (67706)</span>
                            </MutedText>
                        </FormRow>
                        <FormRow label="GitHub id">
                            <Input class="block mt-1 w-full"
                                   placeholder="dev-mart/SkyWarsReloaded"
                                   v-model="plugin.github_link"/>
                            <MutedText>
                                An optional GitHub repository id for the source of this project.
                                You can find this id in the URL of your GitHub repository after github.com.
                                <br>
                                <span class="italic">E.g: https://github.com/dev-mart/SkyWarsReloaded (dev-mart/SkyWarsReloaded)</span>
                            </MutedText>
                        </FormRow>
                        <FormRow label="Donation link">
                            <Input class="block mt-1 w-full"
                                   placeholder="https://www.devmart.net/donate"
                                   v-model="plugin.donation_url"/>
                            <MutedText>
                                A full link to a donation platform of your choice. Users can use this to support
                                you. The Devmart donation link will be used as the default.
                            </MutedText>
                        </FormRow>
                    </FormSection>
                    <FormSection>
                        <FormRow label="Page content">
                            <BBCodeEditor v-model="plugin.features"/>
                        </FormRow>
                    </FormSection>

                    <StickyFooter class="!mt-2">
                        <button class="primary w-full md:w-2/4 p-2 mt-0" type="submit">Save</button>
                    </StickyFooter>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import PluginPreview from "@/components/Pages/Plugins/PluginPreview.vue";
import FormSection from "@/components/Pages/Plugins/forms/FormSection.vue";
import FormRow from "@/components/Pages/Plugins/forms/FormRow.vue";
import Input from "@/components/Common/Input.vue";
import PluginRepository from "@/services/PluginRepository";
import CategoryInput from "@/components/Pages/Plugins/forms/CategoryInput.vue";
import FileInput from "@/components/Common/FileInput.vue";
import StringService from "@/services/StringService";
import MutedText from "@/components/Common/MutedText.vue";
import SwitchInput from "@/components/Common/SwitchInput.vue";
import BBCodeEditor from "@/components/Common/BBCodeEditor.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";

export default {
    name: "EditPluginPage",
    computed: {
        StringService() {
            return StringService
        }
    },
    components: {StickyFooter, BBCodeEditor, SwitchInput, MutedText, FileInput, CategoryInput, Input, FormRow, FormSection, PluginPreview, Navbar},

    async created() {
        const [perms, plugin] = await Promise.all([
            this.fetchPermissions(),
            this.fetchPluginData()
        ]);

        if (!perms || !perms?.modify) {
            this.$router.push({name: "plugin-overview", params: {pluginId: this.pluginId}});
            return;
        }

        this.plugin = plugin;
        this.loadMinecraftVersions();
        this.loadCategories();
        this.loadSale();
        this.initialized = true;
    },

    data() {
        return {
            plugin: null,
            categories: [],
            initialized: false,
            bannerUpload: null,
            minecraftVersions: {},
        }
    },

    props: {
        pluginId: {
            type: String
        }
    },

    methods: {
        async fetchPluginData() {
            try {
                const res = await PluginRepository.fetchPlugin(this.pluginId);
                return res.data;
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
        async fetchPermissions() {
            try {
                const res = await PluginRepository.fetchPluginPermissions(this.pluginId);
                return res.data;
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
        async updateBannerPreview(file) {
            this.plugin.banner_url = file == null ? "https://www.gcnt.net/inc/img/default-plugin-banner.jpg" :
                await StringService.getBase64(file);
        },
        async updateLogoPreview(file) {
            this.plugin.logo_url = file == null ? "https://www.gcnt.net/inc/img/default-plugin-image.png" :
                await StringService.getBase64(file);
        },
        loadMinecraftVersions() {
            const versions = this.plugin.minecraft_versions.split(", ");
            for (let i = 8; i <= 19; i++) {
                let v = `1.${i}`;
                this.minecraftVersions[v] = versions.includes(v);
            }
        },
        updateMinecraftVersion(version) {
            this.minecraftVersions[version] = !this.minecraftVersions[version];
        },
        loadCategories() {
            this.categories = this.plugin.categories.split(",");
        },
        async loadSale() {
            let res = await PluginRepository.fetchUpcomingSales(this.pluginId);

            if (!res.data[0]) return;
            this.plugin.sale = res.data[0];

            if (this.plugin.sale.start_date != null) {
                this.plugin.sale.start_date = this.plugin.sale.start_date.substring(0, 16);
            }
            if (this.plugin.sale.end_date != null) {
                this.plugin.sale.end_date = this.plugin.sale.end_date.substring(0, 16);
            }
        },
        createNewSale() {
            this.plugin.sale = {
                start_date: '',
                end_date: '',
                percentage: '',
            }
        },
        async savePlugin() {
            console.log("saving plugin")

            this.plugin.minecraft_versions = this.minecraftVersions;


            // TODO: Add logic for saving the plugin to the backend.
            console.log(this.plugin);
            try {
                await PluginRepository.editPlugin(this.pluginId, this.plugin);
                this.$router.push({name: "plugin-overview", params: {pluginId: this.pluginId}});
            } catch (e) {
                console.error(e);
            }
        },
    }
}
</script>

<style scoped>

</style>
