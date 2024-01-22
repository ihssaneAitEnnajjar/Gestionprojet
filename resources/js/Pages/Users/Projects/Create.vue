<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
  users: Array, // Add this line to define the users prop
});

const form = useForm({
  name: "",
  description: "",
  start_date: "",
  due_date: "",
  user_id: null, // Initialize user_id as null
  status: "",
  // Ajoutez d'autres champs du formulaire ici
});
</script>

<template>
  <Head title="Create new project" />

  <UserLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('users.projects.index')"
          class="px-3 py-2 text-white font-semibold bg-oceaned hover:bg-indigo-700 rounded"
        >Back</Link>
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Create new project</h1>

        <ul>
          <li :key="error" v-for="error in $page.props.errors">
            {{  error }}
          </li>
        </ul>

        <form @submit.prevent="form.post(route('users.projects.store'))">
          <div class="mt-4">
            <InputLabel for="name" value="Project Name" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              autofocus
              autocomplete="off"
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div class="mt-4">
            <InputLabel for="description" value="Description" />
            <TextInput
              id="description"
              type="text"
              class="mt-1 block w-full"
              v-model="form.description"
              autocomplete="off"
            />
            <InputError class="mt-2" :message="form.errors.description" />
          </div>
          <div class="mt-4">
            <InputLabel for="start_date" value="Start Date" />
            <TextInput
              id="start_date"
              type="date"
              class="mt-1 block w-full"
              v-model="form.start_date"
            />
            <InputError class="mt-2" :message="form.errors.start_date" />
          </div>
          <div class="mt-4">
            <InputLabel for="due_date" value="Due Date" />
            <TextInput
              id="due_date"
              type="date"
              class="mt-1 block w-full"
              v-model="form.due_date"
            />
            <InputError class="mt-2" :message="form.errors.due_date" />
          </div>
          <div class="mt-4">
            <InputLabel for="user_id" value="Assigned to" />
    <select id="user_id" class="mt-1 block w-full rounded-md"  v-model="form.user_id">
        <option value="" disabled>Select a user</option>
        <!-- Loop through the users and display their names as options -->
        <option v-for="user in users" :key="user.id" :value="user.id" :selected="user.id === form.user_id">{{ user.name }}</option>
    </select>
    <InputError class="mt-2" :message="form.errors.user_id" />
          </div>
          <div class="mt-4">
            <InputLabel for="status" value="Status" />
            <TextInput
              id="status"
              type="text"
              class="mt-1 block w-full"
              v-model="form.status"
              autocomplete="off"
            />
            <InputError class="mt-2" :message="form.errors.status" />
          </div>
          <!-- Ajoutez d'autres champs du formulaire ici -->
          <div class="flex items-center mt-4">
            <PrimaryButton
              class="ml-4"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Create
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </UserLayout>
</template>
