<template>
    <div class="flex flex-row h-100">
        <div class="w-100 flex align-items-center m-0 p-0 h-100">
            <Navbar :background="true"/>
            <AuthCard class="w-100">
                <form method="post" @submit.prevent="login">
                    <a href="/login-with-discord"
                       class="button primary flex align-center plain p-3">
                        <div class="flex flex-row gap-2">
                            <font-awesome-icon :icon="['fab', 'discord']" class="icon light"/>
                            <div class="size-18 bold">Log in with Discord</div>
                        </div>
                    </a>

                    <hr>

                    <div>
                        <Label for="username" value="Username or Email"/>
                        <Input id="username"
                               v-model="data.username"
                               class="block mt-1 w-full"
                               type="text"
                               name="username"
                               required
                               autofocus/>
                    </div>
                    <div class="mt-4">
                        <Label for="password" value="Password"/>
                        <Input id="password"
                               v-model="data.password"
                               class="block mt-1 w-full"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"/>
                    </div>

                    <div v-if="'email' in errors" class="text-red-600 mt-2">{{errors.email[0]}}</div>
                    <div class="flex flex-row mt-2 center justify-space-between">
                        <div>
                            <label for="remember_me" class="inline-flex items-center h-100">
                                <Input id="remember_me"
                                       v-model="data.remember"
                                       type="checkbox"
                                       class="rounded"
                                       name="remember"/>

                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>
                        <div>
                            <router-link to="forgot-password" class="underline static size-14">Forgot your password?</router-link>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button class="primary w-100 p-2" :disabled="loggingIn">
                            Log in
                        </button>
                    </div>
                </form>
            </AuthCard>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar";
import AuthCard from "@/components/Common/AuthCard";
import Label from "@/components/Common/Label";
import Input from "@/components/Common/Input";
import {post} from "@/helpers/utils";
import {useAuth} from "@/store/authStore";

export default {
    name: "LoginPage",
    components: {Input, Label, AuthCard, Navbar},

    created() {
        let loggedIn = this.authStore.loggedIn;
        console.log(loggedIn)
        if (loggedIn) {
          this.$router.push("/");
      }
    },

    data() {
        return {
            authStore: useAuth(),
            errors: {},
            data: {
                username: '',
                password: '',
                remember: false
            },
            loggingIn: false
        }
    },

    methods: {
      async login() {
          if (this.loggingIn) return;

          console.log("yeh")
          const response = await this.authStore.login(this.data);

          //
          // this.loggingIn = true;
          // let response = await post('/login', this.data, {Accept: 'application/json'});
          console.log(response)
          // if (response.status === 401) {
          //     const json =  await response.json();
          //     this.errors = json.errors;
          // } else if (response.status === 200) {
          //     // todo user logged in correctly.
          //     this.errors = {};
          //     this.$index.push('/');
          // } else {
          //     this.errors = {
          //         email: ['Something went wrong.']
          //     }
          // }
          this.loggingIn = false;
      }
    },

    computed: {
        redirectUrl() {
            return this.$route.params.redirect ?? "/";
        }
    }
}
</script>

<style scoped>

</style>
