import { createRouter, createWebHistory } from "vue-router";
import HomeSidebar from "@/views/Dashboard/HomeSidebar.vue";
import HomeMain from "@/views/Dashboard/HomeMain.vue";
import LoginView from "@/views/LoginView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "dashboard",
      component: () => import("../views/DashboardView.vue"),
      children: [
        {
          path: "/",
          name: "home",
          components: {
            default: HomeMain,
            sidebar: HomeSidebar,
          },
        },
        {
          path: "/submissao",
          name: "submissao",
          components: {
            default: () => import("../views/Dashboard/SubmissionMain.vue"),
          },
        },
        {
          path: "/usuarios",
          name: "usuarios",
          components: {
            default: () => import("../views/Dashboard/UsersMain.vue"),
          },
        },
      ],
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    {
      path: "/auth/cb",
      name: "auth callback",
      component: () => import("../views/AuthCallbackView.vue"),
    },
  ],
});

router.beforeEach((to, from) => {
  const isAuthenticated = localStorage.getItem("_at") !== null;

  if (!isAuthenticated && to.name !== "login") {
    return { name: "login" };
  }
});

export default router;
