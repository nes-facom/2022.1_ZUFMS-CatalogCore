<script setup lang="ts">
import DashboardHeader from "./DashboardHeader.vue";
import MaterialIcon from "@/components/MaterialIcon.vue";
import { ref, computed, watchEffect } from "vue";
import { useUserStore, userProps, User } from "@/store/users";

const userPropsInfo = {
  id: { title: "Identificador", description: "" },
  email: { title: "E-mail", description: "" },
  allowed_scopes: {
    title: "Escopos de autorização",
    description: "(Separados por espaços)",
  },
};

const usersStore = useUserStore();

usersStore.fetchUsers();

const searchBoxValue = ref("");

const filteredUsers = computed(() =>
  usersStore.filterByEmail(searchBoxValue.value)
);

const onClickUpdate = () => usersStore.updateChangedUsers();
const onClickDelete = () => usersStore.deleteSelectedUsers();
const onCheckboxChange = (userId: User["id"]) => () =>
  usersStore.toggleSelectionUser(userId);

watchEffect(() => console.log(usersStore.error));
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
            :class="`${
              filteredUsers.length === 0 && 'border-red-600/70'
            }  w-[30rem] py-[.6rem] flex items-center rounded-md border-2 border-[#2E688C] bg-[#10527A]`"
          >
            <MaterialIcon
              name="search"
              class="mx-3 text-[#4E93BF] text-[1.2rem]"
            />
            <input
              v-model="searchBoxValue"
              class="w-full bg-transparent placeholder:text-[#4E93BF] text-gray-200 focus-visible:outline-none"
              placeholder="Pesquisar usuário"
            />
          </div>

          <div class="flex">
            <button
              @click="
                usersStore.$state.isCreatingUser = !usersStore.isCreatingUser
              "
              :class="`${
                !usersStore.isCreatingUser
                  ? 'hover:bg-green-600 hover:border-green-700'
                  : 'bg-green-600  cursor-default'
              } w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
            >
              <MaterialIcon name="add" />
            </button>
            <button
              @click="onClickUpdate"
              :disabled="!usersStore.someChangeMade"
              :class="`${
                usersStore.someChangeMade
                  ? 'hover:bg-green-600 hover:border-green-700'
                  : 'opacity-30 cursor-default'
              } w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
            >
              <MaterialIcon name="check" />
            </button>
            <button
              @click="onClickDelete"
              :disabled="!usersStore.someUserSelected"
              :class="`${
                usersStore.someUserSelected
                  ? 'hover:bg-red-600 hover:border-red-700'
                  : 'opacity-30 cursor-default'
              } w-fit p-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
            >
              <MaterialIcon name="delete" />
            </button>
          </div>
        </section>
        <section class="mt-4 pl-16 flex">
          <div class="w-[20rem]" v-for="userProp in userProps" :key="userProp">
            <h4 class="text-white text-xl font-semibold mb-1">
              {{ userPropsInfo[userProp as keyof typeof userPropsInfo].title }}
            </h4>
            <p class="text-gray-300/50 text-sm mb-3">
              {{ userPropsInfo[userProp as keyof typeof userPropsInfo].description }}
            </p>
          </div>
        </section>
      </header>
      <div class="pl-16 flex-col">
        <div
          class="flex w-full h-12"
          v-for="user in filteredUsers"
          :key="user.id"
        >
          <input
            type="checkbox"
            class="-ml-6 mr-2"
            @change="() => onCheckboxChange(user.id)()"
          />
          <input
            v-for="userProp in userProps"
            :key="userProp"
            :disabled="userProp === 'id'"
            :class="`${
              userProp === 'id' ? 'bg-[#2E688C] opacity-40' : 'bg-transparent'
            } w-[20rem] h-full border-2 border-[#2E688C] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white`"
            :value="user[userProp as keyof typeof user]"
            @change="(el) => usersStore.changeUser(user.id, { [userProp]: (el.target as any)?.value })"
          />
          <input
            disabled
            class="bg-[#2E688C] opacity-40 grow h-full border-2 border-[#2E688C] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white"
          />
        </div>

        <div class="flex w-full h-12" v-if="usersStore.isCreatingUser">
          <input type="checkbox" class="-ml-6 mr-2" />
          <input
            v-for="userProp in userProps"
            :key="userProp"
            :disabled="userProp === 'id'"
            v-model="usersStore.$state.newUser[userProp]"
            :class="`${
              userProp === 'id' ? 'bg-[#2E688C] opacity-40' : 'bg-transparent'
            } w-[20rem] h-full border-2 border-[#2E688C] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white`"
          />
          <input
            disabled
            class="bg-[#2E688C] opacity-40 grow h-full border-2 border-[#2E688C] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white"
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
