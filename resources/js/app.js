import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import "../css/app.css";


import App from "./App.vue";
import Login from "./react/Login.vue";
import Dashboard from "./react/Dashboard.vue";
import Services from "./react/Services.vue";
import MyReservations from "./react/MyReservations.vue";

const routes = [
    { path: "/login", component: Login },
    { path: "/", component: Dashboard, meta: { auth: true } },
    { path: "/services", component: Services, meta: { auth: true } },
    { path: "/my-reservations", component: MyReservations, meta: { auth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to) => {
    const token = localStorage.getItem("token");
    if (to.meta.auth && !token) return "/login";
    if (to.path === "/login" && token) return "/";
});

createApp(App).use(router).mount("#app");
