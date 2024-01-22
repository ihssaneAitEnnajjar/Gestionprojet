<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import { onMounted, watch } from "vue";

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const props = defineProps({
  role: {
    type: Object,
    required: true,
  },
  permissions: Array,
});

const form = useForm({
  name: props.role.name,
  permissions: props.role.permissions.map(permission => permission.id),
});

</script>

<template>
  <Head title="Update role" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('admin.roles.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
        >Back</Link>
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Update role</h1>
        <form @submit.prevent="form.put(route('admin.roles.update', role.id))">
          <div class="mt-4">
            <InputLabel for="name" value="Name" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              autofocus
              autocomplete="username"
            />

            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div class="mt-4">
            <InputLabel for="permissions" value="Permissions" />
            <v-select
             :options="permissions"
            :reduce="option => option.id"
            label="name" v-model="form.permissions" multiple />
           
          </div>
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
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Permissions</h1>
        <div class="bg-white">
          <Table>
            <template #default>
              <TableRow
                v-for="rolePermission in role.permissions"
                :key="rolePermission.id"
                class="border-b"
              >
                <TableDataCell>{{ rolePermission.id }}</TableDataCell>
                <TableDataCell>{{ rolePermission.name }}</TableDataCell>
                <TableDataCell class="space-x-4">
                  <Link
                    :href="
                      route('admin.roles.permissions.destroy', [
                        role.id,
                        rolePermission.id,
                      ])
                    "
                    method="DELETE"
                    as="button"
                    class="text-red-400 hover:text-red-600"
                    >Revoke</Link
                  >
                </TableDataCell>
              </TableRow>
            </template>
          </Table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

