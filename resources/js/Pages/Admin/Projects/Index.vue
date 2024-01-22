
<template>
  <div>
    <Head title="projects index" />

    <AdminLayout>
      <div class="flex justify-between">
      <!-- New Project button -->
      <div class="flex justify-between">
          <a href="/admin/projects/create" class="px-3 py-2 text-white font-semibold bg-oceaned hover:bg-indigo-700 rounded flex">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white mr-1">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              New Project
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
                <TableHeaderCell>Project Name</TableHeaderCell>
                <TableHeaderCell>Project Manager</TableHeaderCell>
                <TableHeaderCell>Due date</TableHeaderCell>
                <TableHeaderCell>Status</TableHeaderCell>
                <TableHeaderCell>Action</TableHeaderCell>
              </TableRow>
            </template>
            <template #default>
              <TableRow v-for="project in projects" :key="project.id" class="border-b">
                <TableDataCell>{{ project.id }}</TableDataCell>
                <TableDataCell>{{ project.name }}</TableDataCell>
                <TableDataCell> {{ project.user?.name || 'No User' }}</TableDataCell>                <TableDataCell>{{ project.due_date }}</TableDataCell>
                <TableDataCell>
                  <input type="text" class="status-input " v-model="project.status" readonly :class="{'bg-green-400 text-black':project.status=='Done','bg-yellow-300':project.status=='In progress','bg-pink-400 text-black':project.status=='Not Started','bg-red-500 text-black':project.status=='Cancel'}"/>
                </TableDataCell>
                
                <TableDataCell class="space-x-4 action-column" >
                  <div class="action-wrapper">
                  <Link :href="route('admin.projects.edit', { id: project.id })" class="text-black hover:text-black">Edit</Link>
                  <a href="#" class="text-red-400 hover:text-red-600" @click="confirmDeleteproject(project.id)">Delete</a>
                </div>
                </TableDataCell>

              </TableRow>
            </template>
          </Table>
        </div>
      </div>
    </AdminLayout>
    
    <!-- Modale de confirmation de suppression -->
    <Modal :show="showConfirmDeleteprojectModal" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-semibold text-slate-800">Are you sure to delete this project?</h2>
        <div class="mt-6 flex space-x-4">
          <DangerButton @click="deleteproject">Delete</DangerButton>
          <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref,watch } from 'vue';
import { Inertia } from "@inertiajs/inertia";


const showConfirmDeleteprojectModal = ref(false);
const projectId = ref(null); // Define projectId ref

const closeModal = () => {
  showConfirmDeleteprojectModal.value = false;
};

const confirmDeleteproject = (id) => {
  showConfirmDeleteprojectModal.value = true;
  projectId.value = id; // Store the project ID in projectId ref
};

const deleteproject = async () => {
  if (projectId.value) {
    router.delete(route('admin.projects.destroy', { id: projectId.value }));

    showConfirmDeleteprojectModal.value = false;
  }
};

const props = defineProps({
  projects: Object,
  filters: Object,
});

const search = ref(props.filters.search);

watch(search, (value) => {
  Inertia.get(
    "/admin/projects",
    { search: value},
    {
      preserveState: true,
      replace: true,
    }
  );
});


</script>
<style>
/* Vos styles CSS personnalisés peuvent aller ici */

/* Style pour l'élément <input> de la colonne "Status" */
.status-input {
  border: none; /* Supprimer la bordure */
  border-radius: 999px; /* Pour rendre le fond ovale */
  padding: 5px 15px; /* Ajuster le remplissage selon vos besoins */
  color: #fff; /* Couleur du texte */
  width: 100px; /* Largeur minimale */
  text-align: center; /* Centrer le texte horizontalement */
  font-size: 12px; /* Taille du texte réduite à 12 pixels */
  display: flex; /* Utiliser flexbox pour centrer verticalement */
  justify-content: center; /* Centrer verticalement */
  align-items: center; /* Centrer horizontalement */
  margin-top: -5px; /* Ajuster le texte légèrement vers le haut */
  margin-left: -5px; /* Ajuster le texte légèrement vers la gauche */

}
.action-column {
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Ajouter une ombre élégante */
}

/* Style pour le lien de suppression */
.text-red-400 {
  color: #ff0000; /* Couleur du texte rouge */
}
.text-red-400:hover {
  color: #ff6666; /* Couleur du texte rouge lorsqu'il est survolé */
}
.action-wrapper {
  display: flex;
  justify-content: space-around;
  align-items: center;
  width: 80px; /* Ajuster la largeur des liens d'action selon vos besoins */
}
.edit-link::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: repeating-linear-gradient(45deg, #6366F1, #6366F1 10px, transparent 10px, transparent 20px); /* Créer un fond avec des lignes */
  border-radius: 4px; /* Ajouter le même arrondi que les liens d'action */
  z-index: -1; /* Mettre le fond derrière les liens d'action */
  animation: rotateBackground 2s infinite; /* Animation pour tourner le fond avec des lignes */
}
.action-link:hover {
  transform: scale(1.02); /* Agrandir légèrement le lien au survol */
}

@keyframes rotateBackground {
  0% {
    transform: rotate(0);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>

