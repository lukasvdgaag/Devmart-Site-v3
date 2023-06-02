<template>
    <h1>Account Settings</h1>
    <hr/>

    <Alert v-if="justUpdated" class="mb-4 font-medium" icon="fa-circle-check" type="success">
        {{ isAdmin ? `You saved the account settings of ${user?.username}` : "Your settings have been saved!" }}
    </Alert>
    <Alert v-else-if="Object.keys(errors).length !== 0" class="mb-4 font-medium" icon="fa-circle-xmark" type="error">
        There was an error saving your settings. Please try again.
    </Alert>

    <form class="relative" @submit.prevent="updateUser">
        <h2>Personal Information</h2>

        <div class="mt-2">
            <template v-if="!userLoading">
                <Label value="Username"/>
                <Input v-model="user.username"
                       :disabled="!canChangeUsername"
                       :errors="errors"
                       class="block mt-1 w-full"
                       item="username"
                       placeholder="Username"
                       required/>
                <ValidationError :errors="errors" item="username"/>

                <template v-if="userLoading || !isAdmin">
                    <MutedText v-if="canChangeUsername">
                        You can only change your username once every 30 days.
                    </MutedText>
                    <MutedText v-else>
                        You recently changed your username.
                        You can change it again in {{ getDaysTillNextUsernameChange }} days.
                    </MutedText>
                </template>
            </template>
            <div v-else class="animate-pulse w-full mt-1" role="status">
                <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-32 mb-2"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="mt-3">
            <template v-if="!userLoading">
                <Label class="font-bold" value="Email"/>
                <Input v-model="user.email"
                       :errors="errors"
                       class="block mt-1 w-full"
                       item="email"
                       placeholder="Email"
                       required
                       type="email"/>
                <ValidationError :errors="errors" item="email"/>
            </template>
            <div v-else class="animate-pulse w-full mt-1" role="status">
                <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-32 mb-2"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
            </div>
        </div>

        <h2 class="mt-4">Socials</h2>

        <template v-if="!userLoading">
            <div class="mt-2">
                <Label class="font-bold" value="Discord"/>

                <Alert v-if="discordErrorType === 'success'" class="mt-1 mb-2" type="success">
                    {{ discordError }}
                </Alert>
                <Alert v-else-if="discordErrorType" class="mt-1 mb-2" type="error">
                    {{ discordError }}
                </Alert>

                <div v-if="user?.discord_id && discordInfo" class="flex items-center gap-2 mt-1">
                    <img :src="discordAvatar" alt="avatar" class="h-9 w-9 rounded-full">
                    <div class="text-lg">
                        <span class="font-bold leading-3">{{ discordInfo.username }}</span>#{{ discordInfo.discriminator }}
                    </div>
                </div>

                <a href="/link-discord" v-if="!isAdmin" class="primary flex flex-row w-fit rounded-md align-center gap-2 mt-2 py-1 px-3" type="button">
                    <font-awesome-icon :icon="['fab', 'discord']" class="text-md"/>
                    <span class="text-base font-medium">{{ user?.discord_id ? 'Link a new Discord' : 'Link your Discord' }}</span>
                </a>

                <Input v-if="isAdmin"
                       v-model="user.discord_id"
                       type="number"
                       min="0"
                       class="block mt-1 w-full"
                       placeholder="Discord#1234"
                       required/>
            </div>

            <div class="mt-3">
                <Label class="font-bold" value="Discord Suggestions Notifications"/>
                <DropdownSelect id="discord-sugs-dd"
                                v-model="selectedDSN"
                                :full-width="true"
                                :items="discordSuggestionNotificationsSelect"
                                class="w-full mt-1"
                                description="Type of notifications you want to receive from our Discord Bot regarding your suggestions."
                                header="Discord Suggestions Notifications"
                                placeholder="Select a notification type"/>

                <MutedText>
                    Select what kind of notifications you want to receive from our Discord Bot regarding your
                    suggestions.
                </MutedText>
            </div>

            <div class="mt-3">
                <Label class="font-bold" value="SpigotMC Account ID"/>
                <Input v-model="user.spigot"
                       :disabled="!isAdmin"
                       class="block mt-1 w-full"
                       placeholder="SpigotMC ID"/>

                <MutedText v-if="!userLoading && isAdmin">
                    Changing a user's SpigotMC ID will automatically verify their account, so make sure that
                    it is correct and actually belongs to the user.
                </MutedText>
                <MutedText v-else>
                    It is not possible to manually change your SpigotMC ID. Please head to our
                    <a class="static" href="/discord" target="_blank">Discord Server</a> to link and verify your SpigotMC
                    account to your GCNT account.
                </MutedText>
            </div>
        </template>
        <template v-else>
            <div class="animate-pulse w-full mt-1" role="status">
                <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-32 mb-2"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
            </div>
            <div class="animate-pulse w-full mt-3" role="status">
                <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-32 mb-2"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
            </div>
            <div class="animate-pulse w-full mt-3" role="status">
                <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-32 mb-2"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
            </div>
        </template>

        <h2 class="mt-4">Verification</h2>
        <template v-if="!userLoading">
            <div class="mt-2">
                <Label class="font-bold" value="Account ID"/>
                <DisabledFormText>{{ user?.id ?? '0' }}</DisabledFormText>

                <MutedText v-if="isAdmin">
                    The account ID can be used to verify the GCNT account on any additional GCNT
                    services or to receive quicker support from the staff.
                    You can use this ID to quickly index a user.
                </MutedText>
                <MutedText v-else>
                    Your account ID can be used to verify your GCNT account on any additional GCNT
                    services or to receive quicker support from our staff.
                </MutedText>
            </div>
        </template>
        <template v-else>
            <div class="animate-pulse w-full mt-1" role="status">
                <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-32 mb-2"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
            </div>
            <div class="animate-pulse w-full mt-3" role="status">
                <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-32 mb-2"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
            </div>
        </template>

        <template v-if="!userLoading">
            <h2 class="mt-4">Appearance</h2>
            <div class="flex flex-row gap-4 flex-wrap min-w-[200px] mt-2">
                <button v-for="type in AccountTheme()"
                        :class="[user?.theme === type ? 'border-primary border-3' : 'border-gray-300 dark:border-gray-600 border-2']"
                        class="flex flex-col gap-2 rounded-md p-2 cursor-pointer"
                        type="button"
                        @click="selectTheme(type)">
                    <img :alt="`Theme ${type}`" :src="`/assets/img/theme-${type}.svg`" class="rounded-md">
                    <span class="text-md font-bold capitalize w-full text-center select-none dark:text-gray-300">{{ type }}</span>
                </button>
            </div>
        </template>

        <StickyFooter>
            <button :disabled="loading"
                    class="primary w-full md:w-2/4 p-2 mt-0"
                    type="submit">{{ loading ? "Updating..." : "Save Settings" }}
            </button>
        </StickyFooter>
    </form>
</template>

<script>
import Label from "@/components/Common/Form/Label.vue";
import Input from "@/components/Common/Form/Input.vue";
import UserRepository from "@/services/UserRepository";
import Select from "@/components/Common/Form/Select.vue";
import DiscordSuggestionNotifications from "@/models/DiscordSuggestionNotifications";
import DisabledFormText from "@/components/Common/Form/DisabledFormText.vue";
import AccountTheme from "@/models/AccountTheme";
import MutedText from "@/components/Common/MutedText.vue";
import ValidationError from "@/components/Common/Form/ValidationError.vue";
import Alert from "@/components/Common/Alert.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import AdminEditingWarning from "@/components/Pages/Account/AdminEditingWarning.vue";
import StringService from "@/services/StringService";
import DateService from "@/services/DateService";
import DropdownSelectItemModel from "@/models/DropdownSelectItemModel";
import DropdownSelect from "@/components/Common/Form/DropdownSelect.vue";
import {initDropdowns} from "flowbite";
import User from "@/models/rest/user/User";

export default {
    name: "AccountHome",
    components: {DropdownSelect, AdminEditingWarning, StickyFooter, Alert, ValidationError, DisabledFormText, Select, MutedText, Input, Label},

    data() {
        return {
            errors: {},
            loading: false,
            justUpdated: false,
            selectedDSN: null,
            discordInfo: null,
            discordSuggestionNotificationsSelect: [
                new DropdownSelectItemModel('All messages', 'Get a notification for every action that regards you.', 'ALL_MESSAGES'),
                new DropdownSelectItemModel('Only staff reactions', 'Receive only notifications from staff reactions and responses.', 'ONLY_ADMINS'),
                new DropdownSelectItemModel('Only responses', 'Receive only notifications from responses on your suggestions.', 'ONLY_RESPONSES'),
                new DropdownSelectItemModel('None', 'Do not receive any notifications.', 'NONE'),
            ],
        }
    },

    watch: {
        async 'userLoading'(val) {
            if (!val) {
                await this.user;
                this.selectedDSN = this.discordSuggestionNotificationsSelect.find(e => e.value === this.user?.discord_suggestion_notifications);

                await this.fetchDiscordInfo();
            }
        }
    },

    mounted() {
        initDropdowns();
    },

    computed: {
        StringService() {
            return StringService
        },
        canChangeUsername() {
            if (this.isAdmin) return true;

            const lastChangedDate = new Date(this.user?.username_changed_at);
            const thirtyDaysAgo = DateService.offset(-30);
            return DateService.isBefore(lastChangedDate, thirtyDaysAgo);
        },
        getDaysTillNextUsernameChange() {
            const plus30 = DateService.offset(30, this.user?.username_changed_at);
            return DateService.diffInDays(new Date(), plus30);
        },
        discordAvatar() {
            if (this.discordInfo?.avatar) {
                return `https://cdn.discordapp.com/avatars/${this.user?.discord_id}/${this.discordInfo.avatar}.png`
            } else {
                return '/assets/img/default-discord-avatar.png';
            }
        },
        discordErrorType() {
            return this.$route.query.discord_error;
        },
        discordError() {
            if (!this.discordErrorType) return null;

            switch (this.discordErrorType) {
                case 'discord_in_use':
                    return 'This Discord account is already linked to another Devmart account.';
                case 'success':
                    return 'Your Discord account has been successfully linked!';
                case 'cancel':
                    return 'You have cancelled the Discord linking process.';
                default:
                    return 'Something went wrong while trying to link your Discord account. Please try again.';
            }
        }
    },
    methods: {
        async fetchDiscordInfo() {
            if (!this.user?.discord_id) return;
            const res = await UserRepository.fetchDiscordInformation(this.user?.discord_id);
            this.discordInfo = res.data;
            console.log(this.discordInfo);
        },
        AccountTheme() {
            return AccountTheme
        },
        DiscordSuggestionNotifications() {
            return DiscordSuggestionNotifications
        },
        selectTheme(theme) {
            this.user.theme = theme;
        },
        async updateUser() {
            if (this.loading) return;
            this.loading = true;
            this.justUpdated = false;

            try {
                this.user.discord_suggestion_notifications = this.selectedDSN?.value ?? null;
                this.user.updateFromJson(await UserRepository.updateUserById(this.user?.id, this.user));
                this.selectedDSN = this.discordSuggestionNotificationsSelect.find(e => e.value === this.user?.discord_suggestion_notifications);

                this.errors = {};
                this.justUpdated = true;
            } catch (e) {
                if (e.response) {
                    this.errors = e.response.data.errors;
                } else {
                    this.errors = {}
                }
            }
            this.loading = false;
        }
    },

    props: {
        user: {
            type: [User, null],
            required: true
        },
        isAdmin: {
            type: Boolean,
            required: true
        },
        userLoading: {
            type: Boolean,
            required: true
        },
        userId: {
            type: Number,
            required: true
        },
    }
}
</script>

<style scoped>
.account-theme {

}
</style>
