import HomePage from "@/pages/HomePage";
import {createRouter, createWebHistory} from 'vue-router';
import LoginPage from "@/pages/auth/LoginPage";
import RegisterPage from "@/pages/auth/RegisterPage";

const routes = [
    {
        path: '/',
        component: HomePage
    },
    {
        path: '/login',
        component: LoginPage
    },
    {
        path: '/register',
        component: RegisterPage
    }
];

export default createRouter({
    history: createWebHistory(),
    routes: routes
});
