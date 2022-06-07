import { createRouter, createWebHistory } from "vue-router";
import DashboardView from "@/views/DashboardView.vue";
import HomeSidebar from "@/components/Dashboard/HomeSidebar.vue";
import HomeMain from "@/components/Dashboard/HomeMain.vue";
import LoginView from "@/views/LoginView.vue";
import ZUFMSSpreadsheet from "@/components/ZUFMSSpreadsheet/index.vue";
import SubmissionMain from "@/components/Dashboard/SubmissionMain.vue";

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
          path: "/etiquetas",
          name: "etiquetas",
          components: { default: ZUFMSSpreadsheet },
        },
        {
          path: "/emprestimos",
          name: "emprestimos",
          components: { default: ZUFMSSpreadsheet },
        },
        {
          path: "/submissao",
          name: "submissao",
          components: { default: SubmissionMain },
        },
        {
          path: "/usuarios",
          name: "usuarios",
          components: {
            default: () => import("../components/Dashboard/UsersMain.vue"),
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

export default router;
