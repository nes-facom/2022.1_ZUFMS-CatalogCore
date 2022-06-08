<script setup lang="ts">
import DashboardHeader from "./DashboardHeader.vue";
import MaterialIcon from "../icons/MaterialIcon.vue";
import { computed, nextTick, reactive, ref, watch, watchEffect } from "vue";
import { api } from "@/api";

const users = ref<any>([]);

const selectedUsers = reactive<any>({});

const someUserSelected = ref(false);

const someUpdateMade = ref(false);

const fetchUsers = () =>
  api
    .withAccessToken(localStorage.getItem("_at") ?? "")
    .users.usersGetAll()
    .then(({ data }) => {
      users.value = data;
      nextTick(() => (someUpdateMade.value = false));
    });

fetchUsers();

const insertingNewUser = ref(false);
const newUser = ref<any>({});

watch([users, newUser], () => (someUpdateMade.value = true), { deep: true });

const userProps = computed(() => Object.keys(users.value?.[0] ?? {}));

const onCheckboxChange = (user: any) => (e: Event) => {
  if ((e.target as any | null)?.checked) {
    selectedUsers[user.id] = true;
  } else {
    delete selectedUsers[user.id];
  }

  someUserSelected.value = Object.keys(selectedUsers).length > 0;
};

const onClickDelete = () =>
  Object.keys(selectedUsers).forEach((userId) => {
    api
      .withAccessToken(localStorage.getItem("_at") ?? "")
      .users.usersDeleteOne(userId)
      .then(fetchUsers);
  });

const onClickUpdate = () => {
  if (insertingNewUser.value) {
    api
      .withAccessToken(localStorage.getItem("_at") ?? "")
      .users.usersCreateOne(newUser.value)
      .then(() => {
        insertingNewUser.value = false;
        fetchUsers();
      });
  }

  users.value.forEach(({ id, ...userData }: any) =>
    api
      .withAccessToken(localStorage.getItem("_at") ?? "")
      .users.usersUpdateOne(id, userData)
      .then(fetchUsers)
  );
};

const searchBoxValue = ref("");

const filteredUsers = () =>
  users.value.filter(({ email }: { email: string }) =>
    email.startsWith(searchBoxValue.value)
  );
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
              filteredUsers().length === 0 && 'border-red-600/70'
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
              @click="insertingNewUser = !insertingNewUser"
              :class="`${
                !insertingNewUser
                  ? 'hover:bg-green-600 hover:border-green-700'
                  : 'bg-green-600  cursor-default'
              } w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
            >
              <MaterialIcon name="add" />
            </button>
            <button
              @click="onClickUpdate"
              :disabled="!someUpdateMade"
              :class="`${
                someUpdateMade
                  ? 'hover:bg-green-600 hover:border-green-700'
                  : 'opacity-30 cursor-default'
              } w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
            >
              <MaterialIcon name="check" />
            </button>
            <button
              @click="onClickDelete"
              :disabled="!someUserSelected"
              :class="`${
                someUserSelected
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
              {{ userProp }}
            </h4>
          </div>
        </section>
      </header>
      <div class="pl-16 flex-col">
        <div
          class="flex w-full h-12"
          v-for="user in filteredUsers()"
          :key="user.id"
        >
          <input
            type="checkbox"
            class="-ml-6 mr-2"
            @change="(e) => onCheckboxChange(user)(e)"
          />
          <input
            v-for="userProp in Object.keys(users[0])"
            :key="userProp"
            :disabled="userProp === 'id'"
            :class="`${
              userProp === 'id' ? 'bg-[#2E688C] opacity-40' : 'bg-transparent'
            } w-[20rem] h-full border-2 border-[#2E688C] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white`"
            :value="user[userProp as keyof typeof user]"
            @change="(el) => user[userProp as keyof typeof user] = (el.target as any)?.value"
          />
        </div>

        <div class="flex w-full h-12" v-if="insertingNewUser">
          <input type="checkbox" class="-ml-6 mr-2" />
          <input
            v-for="userProp in Object.keys(users[0])"
            :key="userProp"
            :disabled="userProp === 'id'"
            :class="`${
              userProp === 'id' ? 'bg-[#2E688C] opacity-40' : 'bg-transparent'
            } w-[20rem] h-full border-2 border-[#2E688C] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white`"
            @change="(el) => newUser[userProp as keyof typeof newUser] = (el.target as any)?.value"
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
