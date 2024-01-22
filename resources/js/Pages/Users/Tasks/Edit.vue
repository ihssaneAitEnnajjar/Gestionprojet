<template>
    <div>
      <Head title="Update task" />
  
      <UserLayout>

        <div class="max-w-7xl mx-auto py-4">
          <div class="flex justify-between">
            <Link
              :href="route('users.tasks.index')"
              class="px-3 py-2 text-white font-semibold bg-oceaned hover:bg-indigo-700 rounded"
            >Back</Link>
          </div>
          <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-indigo-700">Update task</h1>
            <form @submit.prevent="form.put(route('users.tasks.update',task.id))">
              <div class="mt-4">
                <InputLabel for="name" value="Task Name" />
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
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input
                  id="start_date"
                  type="date"
                  v-model="form.start_date"
                  class="mt-1 block w-full"
                  required
                />
                <InputError class="mt-2" :message="form.errors.start_date" />
              </div>
              <div class="mt-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input
                  id="due_date"
                  type="date"
                  v-model="form.due_date"
                  class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="form.errors.due_date" />
              </div>
              <div class="mt-4">
        <InputLabel for="user_id" value="Assigned to" />
        <select
          id="user_id"
          class="mt-1 block w-full rounded-md"
          v-model="form.user_id"
        >
          <option value="" disabled>Select a user</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
        <InputError class="mt-2" :message="form.errors.user_id" />
      </div>
      <div class="mt-4">
        <InputLabel for="sprint_id" value="Sprint" />
        <select
          id="sprint_id"
          class="mt-1 block w-full rounded-md"
          v-model="form.sprint_id"
        >
          <option value="" disabled>Select a sprint</option>
          <option v-for="(name, id) in sprints" :key="id" :value="id">
            {{ name }}
          </option>
        </select>
        <InputError class="mt-2" :message="form.errors.sprint_id" />
      </div>

              <div class="mt-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <input
                  id="status"
                  type="text"
                  v-model="form.status"
                  class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="form.errors.status" />
              </div>
              <!-- Add other fields as needed... -->
  
              <div class="flex items-center mt-4">
                <PrimaryButton
                  class="ml-4"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Update
                </PrimaryButton>
              </div>
            </form>
          </div>
        </div>
      </UserLayout>
    </div>
  </template>
  
  <script setup>
  import { Head, Link, useForm ,router} from "@inertiajs/vue3";
  import PrimaryButton from "@/Components/PrimaryButton.vue";
  import InputLabel from "@/Components/InputLabel.vue";
  import InputError from "@/Components/InputError.vue";
  import TextInput from "@/Components/TextInput.vue";
  import { defineProps } from "vue";
import UserLayout from "@/Layouts/UserLayout.vue";
  
  const props = defineProps({
   task: {
      type: Object,
      required: true,
    },
    sprints: Object,
    users: Array, // Add this line to define the users prop



  });
  const form = useForm({
  name: props.task.name,
  start_date: props.task.start_date,
  due_date: props.task.due_date,
  status: props.task.status,
  user_id: props.task.user_id,
  sprint_id: props.task.sprint_id,
  
  
  // Add other fields as needed...
});


  
  </script>
  
  <style>
  /* Your custom CSS styles can go here */
  </style>
  