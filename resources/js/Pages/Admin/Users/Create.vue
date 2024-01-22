<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";


defineProps({
  roles: Object,
})

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  role: null,
});

const submit = () => {
  form.post(route("admin.users.store"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>

<template>
  <AdminLayout>
    <Head title="Create user" />
    <div class="max-w-7xl mx-auto mt-4">
      <div class="flex justify-between">
        <Link
          :href="route('admin.users.index')"
          class="px-3 py-2 text-white font-semibold bg-oceaned hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
    </div>
    <div class="max-w-md mx-auto mt-6 p-6 bg-slate-100">
      <form @submit.prevent="submit">
        <div>
          <InputLabel for="name" value="Name" />

          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />

          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="mt-4">
          <InputLabel for="email" value="Email" />

          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="Password" />

          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="new-password"
          />

          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
          <InputLabel for="password_confirmation" value="Confirm Password" />

          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />

          <InputError
            class="mt-2"
            :message="form.errors.password_confirmation"
          />
        </div>
        <div class="mt-4">
      <InputLabel for="roles" value="Roles" />

      <select
        id="roles"
        class="mt-1 block w-full  rounded-md"
        v-model="form.role"
      >
        <option value="" disabled>Select some roles</option>
        <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
      </select>

      <InputError class="mt-2" :message="form.errors.role" />
    </div>
   
    <ul role="list" class="mt-4 divide-y divide-gray-100 rounded-md border border-gray-200">
      <li 
      :key="name" v-for="name in roles[form.role]?.permissions.map(permission => permission.name)"
      class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
              <div class="flex w-0 flex-1 items-center">
                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                  <span class="truncate font-medium">{{ name }}</span>
                </div>
              </div>
            </li>
    </ul>
          

        <div class="flex items-center justify-end mt-4">
          <PrimaryButton
            class="ml-4"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Create User
          </PrimaryButton>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
