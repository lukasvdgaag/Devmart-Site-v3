<template>
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
                <img :src="bannerUrl" alt="Banner image" class="plugin-preview-banner"/>

                <FileInput accept=".jpg,.jpeg,.png,.webp,.svg,.avif" @upload="updateBannerPreview"/>
            </FormRow>
            <FormRow label="Logo Image">
                <img :src="iconUrl" alt="Logo image" class="icon huge rounded-xl"/>

                <FileInput accept=".jpg,.jpeg,.png,.webp,.svg,.avif" @upload="updateLogoPreview"/>
            </FormRow>
        </FormSection>

        <FormSection>
            <FormRow label="Price">
                <Input v-model="plugin.price"
                       class="block w-full"
                       min="0"
                       placeholder="Price"
                       required
                       type="number"/>
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
                            <Input v-model="plugin.sale.percentage"
                                   class="col-span-2 w-full rounded-r-none md:rounded-r-md"
                                   max="100"
                                   min="0"
                                   placeholder="%"
                                   required
                                   type="number"/>
                            <Input v-model="plugin.sale.start_date"
                                   class="col-span-5 w-full rounded-none md:rounded-md"
                                   placeholder="Start date"
                                   required
                                   type="datetime-local"/>
                            <Input v-model="plugin.sale.end_date"
                                   class="col-span-5 w-full rounded-l-none md:rounded-l-md"
                                   placeholder="End date"
                                   type="datetime-local"
                            />
                        </div>
                        <button class="text-lg h-12 w-12 flex align-center mt-0" type="button" @click.prevent="plugin.sale = null">
                            <font-awesome-icon class="text-red-500 w-full h-full" icon="fa-circle-xmark"/>
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
                <SwitchInput v-model="plugin.custom" :checked="plugin.custom" class="mt-2"/>
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
                            <Input :checked="minecraftVersions[version]"
                                   class="mr-2"
                                   type="checkbox"
                                   @change="updateMinecraftVersion(version)"/>
                            {{ version }}
                        </label>
                    </li>
                </ul>
            </FormRow>
            <FormRow label="(Soft) Dependencies">
                <Input v-model="plugin.dependencies"
                       class="block w-full"
                       placeholder="Enter any (soft) dependencies"/>
            </FormRow>
        </FormSection>

        <FormSection>
            <FormRow label="Spigot id">
                <Input v-model="plugin.spigot_link"
                       class="block mt-1 w-full"
                       min="0"
                       placeholder="67706"
                       type="number"/>
                <MutedText>
                    If this project has an associated SpigotMC resource page, please enter the numeric id of
                    the page. You can find it at the end of the resource page's URL, after the dot.
                    <br>
                    <span class="italic">E.g: https://www.spigotmc.org/resources/additions-plus.67706. (67706)</span>
                </MutedText>
            </FormRow>
            <FormRow label="GitHub id">
                <Input v-model="plugin.github_link"
                       class="block mt-1 w-full"
                       placeholder="dev-mart/SkyWarsReloaded"/>
                <MutedText>
                    An optional GitHub repository id for the source of this project.
                    You can find this id in the URL of your GitHub repository after github.com.
                    <br>
                    <span class="italic">E.g: https://github.com/dev-mart/SkyWarsReloaded (dev-mart/SkyWarsReloaded)</span>
                </MutedText>
            </FormRow>
            <FormRow label="Donation link">
                <Input v-model="plugin.donation_url"
                       class="block mt-1 w-full"
                       placeholder="https://www.devmart.net/donate"/>
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
            <button :class="{'bg-opacity-50':isSaving}" :disabled="isSaving" class="primary w-full md:w-2/4 p-2 mt-0" type="submit">
                {{ isSaving ? 'Saving...' : 'Save' }}
            </button>
        </StickyFooter>
    </form>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import PluginPreview from "@/components/Pages/Plugins/PluginPreview.vue";
import FormSection from "@/components/Pages/Plugins/forms/FormSection.vue";
import FormRow from "@/components/Pages/Plugins/forms/FormRow.vue";
import Input from "@/components/Common/Form/Input.vue";
import PluginRepository from "@/services/PluginRepository";
import CategoryInput from "@/components/Pages/Plugins/forms/CategoryInput.vue";
import FileInput from "@/components/Common/FileInput.vue";
import StringService from "@/services/StringService";
import MutedText from "@/components/Common/MutedText.vue";
import SwitchInput from "@/components/Common/Form/SwitchInput.vue";
import BBCodeEditor from "@/components/Common/BBCodeEditor.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import UploadService from "@/services/UploadService";
import Plugin from "@/models/rest/Plugin";
import Sale from "@/models/rest/Sale";

export default {
    name: "EditPluginPage",
    computed: {
        StringService() {
            return StringService
        },
        bannerUrl() {
            if (!this.plugin.banner_url) {
                return this.originalBanner ?? '/assets/img/default-plugin-banner.png';
            }

            if (this.plugin.banner_url.startsWith('data:')) {
                return this.plugin.banner_url;
            } else {
                return `/assets/img/${this.plugin.banner_url}`;
            }
        },
        iconUrl() {
            if (!this.plugin.logo_url) {
                return this.originalIcon ?? 'img/logo.png';
            }

            if (this.plugin.logo_url.startsWith('data:')) {
                return this.plugin.logo_url;
            } else {
                return `/assets/img/${this.plugin.logo_url}`;
            }
        },
    },
    components: {
        StickyFooter, BBCodeEditor, SwitchInput, MutedText, FileInput, CategoryInput, Input, FormRow, FormSection, PluginPreview, Navbar
    },

    async created() {
        this.originalBanner = this.plugin.banner_url;
        this.originalIcon = this.plugin.logo_url;

        this.loadMinecraftVersions();
        this.loadCategories();
        await this.loadSale();
    },

    data() {
        return {
            categories: [],
            originalBanner: null,
            originalIcon: null,
            minecraftVersions: {},
            isSaving: false,
        }
    },

    props: {
        pluginId: {
            type: String
        },
        plugin: {
            type: Plugin,
        }
    },

    methods: {
        async updateBannerPreview(file) {
            let base64 = await UploadService.resizeImage(await StringService.getBase64(file), 1180, 664);

            this.plugin.banner_url = file == null ? "https://www.gcnt.net/inc/img/default-plugin-banner.jpg" :
                base64;
        },
        async updateLogoPreview(file) {
            let base64 = await UploadService.resizeImage(await StringService.getBase64(file), 96, 96);

            this.plugin.logo_url = file == null ? "https://www.gcnt.net/inc/img/default-plugin-image.png" :
                base64;
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
            this.plugin.sale = new Sale();
        },
        async savePlugin() {
            this.isSaving = true;
            this.plugin.minecraft_versions = this.minecraftVersions;
            this.plugin.logo_url = this.plugin.logo_url === this.originalIcon ? null : this.plugin.logo_url;
            this.plugin.banner_url = this.plugin.banner_url === this.originalBanner ? null : this.plugin.banner_url;
            this.plugin.categories = this.categories.join(',');

            try {
                await PluginRepository.editPlugin(this.pluginId, this.plugin);
                this.$router.push({name: "plugin-overview", params: {pluginId: this.pluginId}});
            } catch (e) {
                console.error(e);
            }
            this.isSaving = false;
        },
    }
}
</script>

<style scoped>

</style>
