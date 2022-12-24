import {useAuth} from "@/store/authStore";

const guestOnlyRoutes = [
    "login",
    "register",
    "forgotPassword",
    "resetPassword"
]

export const checkAuthenticated = async (to) => {
    const authStore = useAuth();
    await authStore.getAuthUser();

    // redirecting the user to the login page when request a page that requires authentication
    // or when the page requires the user to be an admin while they're not.
    if ((to?.meta?.requireAuth && !authStore.user) || (to?.meta?.requireAdmin && !authStore?.user?.admin)) {
        return {
            name: 'login',
            query: {
                redirect: to.fullPath
            }
        }
    }

    // redirecting the user to the home page when they are logged in
    // while requesting a page viewable for quests only.
    if (authStore.user && guestOnlyRoutes.includes(to.name)) {
        return {
            name: 'home'
        }
    }
}
