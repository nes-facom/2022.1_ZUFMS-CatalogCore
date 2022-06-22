import { User as UserApi, userApi } from "@/api";
import { wait } from "@/util/async";
import { defineStore } from "pinia";

export type User = Required<UserApi>;

export const userProps = ["id", "email", "allowed_scopes"] as (keyof User)[];

type WithPlainScopes<T = User> = T & { allowed_scopes: string };

type State = {
  users: User[];
  userChanges: Record<User["id"], Partial<WithPlainScopes<User>>>;
  selectedUsers: Record<User["id"], true>;
  newUser: Partial<WithPlainScopes<User>>;
  isCreatingUser: boolean;
  loading: boolean;
  error?: Error;
};

export const useUserStore = defineStore("userStore", {
  state: () =>
    ({
      users: [],
      userChanges: {},
      selectedUsers: {},
      isCreatingUser: false,
      loading: false,
      newUser: {},
    } as State),
  getters: {
    usersWithChanges: (state) =>
      state.users.map((user) => ({
        ...user,
        ...(state.userChanges[user.id] ?? {}),
      })),
    someUserSelected: (state) => Object.keys(state.selectedUsers).length > 0,
    someChangeMade: (state) =>
      Object.keys(state.userChanges).length +
        Object.keys(state.newUser).length >
      0,
  },
  actions: {
    async fetchUsers() {
      this.loading = true;

      try {
        this.users = (await userApi.users.usersGetAll()).data as User[];
      } catch (err) {
        if (err instanceof Error) {
          this.error = err;
        }
      }

      this.loading = false;
    },

    toggleSelectionUser(userId: User["id"]) {
      if (this.selectedUsers[userId]) {
        delete this.selectedUsers[userId];
      } else {
        this.selectedUsers[userId] = true;
      }
    },

    async deleteSelectedUsers() {
      this.loading = true;

      Object.keys(this.selectedUsers).forEach(async (userId) => {
        try {
          await userApi.users.usersDeleteOne(userId);
        } catch (err) {
          if (err instanceof Error) {
            this.error = err;
          }
        }

        this.loading = false;
        await wait(500);
        this.fetchUsers();
      });
    },
    changeUser(id: User["id"], data: Partial<WithPlainScopes<User>>) {
      this.userChanges[id] = {
        ...(this.userChanges[id] ?? {}),
        ...data,
      };
    },
    async updateChangedUsers() {
      this.loading = true;

      if (this.isCreatingUser) {
        try {
          await userApi.users.usersCreateOne({
            ...this.newUser,
            allowed_scopes: this.newUser?.allowed_scopes?.trim().split(" "),
          });

          this.isCreatingUser = false;
          this.newUser = {};
          await this.fetchUsers();
        } catch (err) {
          if (err instanceof Error) {
            this.error = err;
            this.loading = false;

            return;
          }
        }
      }

      Object.entries(this.userChanges).forEach(async ([id, userData]) => {
        try {
          await userApi.users.usersUpdateOne(id, {
            ...userData,
            allowed_scopes: userData.allowed_scopes?.trim().split(" "),
          });
        } catch (err) {
          if (err instanceof Error) {
            this.error = err;
            this.loading = false;
            return;
          }
        }
      });

      this.userChanges = {};
      this.loading = false;
      await wait(500);
      this.fetchUsers();
    },

    filterByEmail(value: string) {
      return this.usersWithChanges.filter(({ email }) =>
        email.startsWith(value)
      );
    },
  },
});
