import { createRouter, createWebHistory } from "vue-router";
import HomeSidebar from "@/views/Dashboard/HomeSidebar.vue";
import HomeMain from "@/views/Dashboard/HomeMain.vue";
import LoginView from "@/views/LoginView.vue";
import { useAuthStore } from "@/store/auth";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "dashboard",
      component: () => import("../views/DashboardView.vue"),
      meta: { requiresAuth: true },
      children: [
        {
          path: "/",
          name: "home",
          components: {
            default: HomeMain,
            sidebar: HomeSidebar,
          },
          meta: { requiresAuth: true },
        },
        {
          path: "/submissao",
          name: "submissao",
          components: {
            default: () => import("../views/Dashboard/SubmissionMain.vue"),
          },
          meta: { requiresAuth: true },
        },
        {
          path: "/usuarios",
          name: "usuarios",
          components: {
            default: () => import("../views/Dashboard/UsersMain.vue"),
          },
          meta: { requiresAuth: true },
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

router.beforeEach((to) => {
  const authStore = useAuthStore();

  if (!authStore.isAuthenticated && to.meta.requiresAuth) {
    return { name: "login" };
  }
});

export default router;
