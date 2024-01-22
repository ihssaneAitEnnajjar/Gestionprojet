<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref,watch } from 'vue';
import { Inertia } from "@inertiajs/inertia";
const props = defineProps({
  users: Array,
  filters: Object,
});

const search = ref(props.filters.search);

watch(search, (value) => {
  // Send an Inertia GET request with the search term
  Inertia.get(
    route("admin.users.index"), // Make sure the route name is correct
    { search: value },
    {
      preserveState: true,
      replace: true,
    }
  );
});

//const showConfirmDeleteUserModal = ref(false)

//const confirmDeleteUser = () => {
     // showConfirmDeleteUserModal.value = true;
//}//

//const closeModal = () => {
  //showConfirmDeleteUserModal.value = false;
//}

//const deleteUser = (id) => {
   //form.delete(route('users.destroy', id), {
    //onSuccess: () => closeModal()
   //});
//}
</script>
<style>
.action-column {
  /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); Ajouter une ombre élégante */
}


.action-link:hover {
  transform: scale(1.02); /* Agrandir légèrement le lien au survol */
}
h1 {
  color: #333;
}

.text-2xl {
  font-size: 1.5rem;
}

/* Center align the "Action" cell content */
.text-center {
  text-align: center;
}

/* Add subtle hover effect to action links */
.action-column a:hover {
  transform: scale(1.05);
}

/* Add a slight shadow to action links */
.action-column a {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* Style for the delete button */
.text-red-400 {
  color: #ff0000;
}
.text-red-400:hover {
  color: #ff6666;
}

</style>

<template>
  <Head title="Users index" />

  <AdminLayout>
    <div class="flex justify-between">
      <!-- New User button -->
      <div class="flex justify-between">
          <a href="/admin/users/create" class="px-3 py-2 text-white font-semibold bg-oceaned hover:bg-indigo-700 rounded flex">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white mr-1">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              New User
          </a>
      </div>
      <!-- Search bar -->
    <div>
        <div class="relative flex items-center text-gray-400 focus-within:text-cyan-400">
            <span class="absolute left-4 h-6 flex items-center pr-3 border-r border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 fill-current" viewBox="0 0 35.997 36.004">
                    <path id="Icon_awesome-search" data-name="search" d="M35.508,31.127l-7.01-7.01a1.686,1.686,0,0,0-1.2-.492H26.156a14.618,14.618,0,1,0-2.531,2.531V27.3a1.686,1.686,0,0,0,.492,1.2l7.01,7.01a1.681,1.681,0,0,0,2.384,0l1.99-1.99a1.7,1.7,0,0,0,.007-2.391Zm-20.883-7.5a9,9,0,1,1,9-9A8.995,8.995,0,0,1,14.625,23.625Z"></path>
                </svg>
            </span>
            <input v-model="search" type="search" name="leadingIcon" id="leadingIcon" placeholder="Search here" class="w-full pl-14 pr-4 py-2.5 rounded-xl text-sm text-gray-600 outline-none border border-gray-300 focus:border-cyan-300 transition">
        </div>
    </div>
    
</div>
    <div class="max-w-7xl mx-auto py-4">
      
      <div class="mt-6">
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>ID</TableHeaderCell>
              <TableHeaderCell>Name</TableHeaderCell>
              <TableHeaderCell>Email</TableHeaderCell>
              <TableHeaderCell class="flex justify-center">Action</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow v-for="user in users" :key="user.id" class="border-b">
              <TableDataCell>{{ user.id }}</TableDataCell>
              <TableDataCell>{{ user.name }}</TableDataCell>
              <TableDataCell>{{ user.email }}</TableDataCell>
              <TableDataCell class="space-x-4 action-column flex justify-center items-center ">
                <Link :href="route('admin.users.edit',{id:user.id} )"  class="text-black hover:text-green-600">Edit</Link>
                <Link :href="route('admin.users.destroy',{id:user.id} )"  class="text-red-400 hover:text-green-600">Delete</Link>

               <Modal :show="showConfirmDeleteUserModal" @close="closeModal">
                 <div class="p-6">
                  <h2 class="text-lg font-semibold text-slate-800">Are you sure to delete this user?</h2>
                  <div class="mt-6 flex space-x-4">
                    <DangerButton @click="deleteUser(user.id)">Delete</DangerButton>
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                  </div>
                 </div>
               </Modal>
              </TableDataCell>
            </TableRow>
          </template>
        </Table>
      </div>
    </div>
  </AdminLayout>
</template>