<script setup lang="ts">
import DashboardHeader from "./DashboardHeader.vue";
import MaterialIcon from "../icons/MaterialIcon.vue";
import { reactive, ref, watchEffect } from "vue";

const users = [
  { id: "0", email: "admin@ufms.br", scopes: ["admin"] },
  { id: "1", email: "usuario@ufms.br", scopes: ["occurrences:read"] },
  {
    id: "2",
    email: "outro.usuario@ufms.br",
    scopes: ["occurrences", "users:read"],
  },
];

const selectedUsers = reactive<any>({});

const someUserSelected = ref<boolean>(false);

const onCheckboxChange = (user: any) => (e: Event) => {
  if (e.target.checked) {
    selectedUsers[user.id] = true;
  } else {
    delete selectedUsers[user.id];
  }

  someUserSelected.value = Object.keys(selectedUsers).length > 0;
};
</script>

<template>
  <div class="flex flex-col w-full h-full pt-10">
    <Transition name="slide">
      <DashboardHeader
        class="px-16"
        title="Usuários"
        subtitle="Gerencie os usuários da aplicação"
      >
      </DashboardHeader>
    </Transition>
    <div class="flex-1 w-full overflow-x-scroll h-full">
      <header>
        <section
          class="flex justify-between p-6 pl-14 border-b-2 border-[#2E688C]"
        >
          <div
            class="w-[30rem] py-[.6rem] flex items-center rounded-md border-2 border-[#2E688C] bg-[#10527A]"
          >
            <MaterialIcon
              name="search"
              class="mx-3 text-[#4E93BF] text-[1.2rem]"
            />
            <input
              class="bg-transparent placeholder:text-[#4E93BF] text-gray-200 focus-visible:outline-none"
              placeholder="Pesquisar usuário"
            />
          </div>

          <button
            :disabled="someUserSelected"
            :class="`${
              someUserSelected
                ? 'hover:bg-red-600 hover:border-red-700'
                : 'opacity-30 cursor-default'
            } w-fit p-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
          >
            <MaterialIcon name="delete" />
          </button>
        </section>
        <section class="mt-4 pl-16 flex">
          <div
            class="w-[20rem]"
            v-for="userProp in Object.keys(users[0])"
            :key="userProp"
          >
            <h4 class="text-white text-xl font-semibold mb-1">
              {{ userProp }}
            </h4>
          </div>
        </section>
      </header>
      <div class="pl-16 flex-col">
        <div class="flex w-full h-12" v-for="user in users" :key="user.id">
          <input
            type="checkbox"
            class="-ml-6 mr-2"
            @change="(e) => onCheckboxChange(user)(e)"
          />
          <input
            v-for="userProp in Object.keys(users[0])"
            :key="userProp"
            class="w-[20rem] h-full bg-transparent border-2 border-[#2E688C] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white"
            :value="user[userProp as keyof typeof user]"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.disable-pointer-events * {
  pointer-events: none;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.1s ease-in-out;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateY(-100px);
  opacity: 0;
}
</style>
