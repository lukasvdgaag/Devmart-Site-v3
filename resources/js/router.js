import HomeView from "@/views/HomeView";
import {createWebHistory, createRouter} from 'vue-router';

const routes = [
    {
        path: '/',
        component: HomeView
    }
];

export default createRouter({
    history: createWebHistory(),
    routes: routes
});
