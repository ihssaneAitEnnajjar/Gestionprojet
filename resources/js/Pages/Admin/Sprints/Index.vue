
<template>
    <div>
      <Head title="sprints index" />
  
      <AdminLayout>
        <div class="flex justify-between">
      <!-- New Sprint button -->
      <div class="flex justify-between">
          <a href="/admin/sprints/create" class="px-3 py-2 text-white font-semibold bg-oceaned hover:bg-indigo-700 rounded flex">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white mr-1">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              New Sprint
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
            <Table >
              <template #header>
                <TableRow class="text-sm  border-yellow-600	">
                  <TableHeaderCell>Sprint Name</TableHeaderCell>
                  <TableHeaderCell> Responsable</TableHeaderCell>
                  <TableHeaderCell>Due date</TableHeaderCell>
                  <TableHeaderCell>Status</TableHeaderCell>
                  <TableHeaderCell>Priority</TableHeaderCell>
                  <TableHeaderCell>Action</TableHeaderCell>
                </TableRow>
              </template>
              <template #default>
                <TableRow v-for="sprint in sprints" :key="sprint.id" class="border-b">
                  <TableDataCell>{{ sprint.name }}</TableDataCell>
                  <TableDataCell>{{ sprint.user ? sprint.user.name : 'No User' }}
</TableDataCell> 
                  <TableDataCell>{{ sprint.due_date }}</TableDataCell>
                  <TableDataCell>
                    <input type="text" class="status-input flex items-center" v-model="sprint.status" readonly :class="{'bg-sky-400 text-black':sprint.status=='Dev In progress','bg-green-500 text-black':sprint.status=='Done','bg-indigo-400 text-black':sprint.status=='To do','bg-red-500 text-black':sprint.status=='Dev Blocked'}"/>
                  </TableDataCell>
                  <TableDataCell class="priority-cell">
  <span class="priority-label btn-primary flex items-center" :class="{'bg-red-500 text-black':sprint.priority=='HIGH','bg-orange-400 text-black':sprint.priority=='LOW','bg-rose-400 text-black':sprint.priority=='MEDIUM'}">{{ sprint.priority  }} </span>
</TableDataCell>
                  <TableDataCell class="space-x-4 action-column" >
                    <div class="action-wrapper">
                      <Link :href="route('admin.sprints.edit', { id: sprint.id })" class="text-black-400 hover:text-black mr-4">Edit</Link>

                    <a href="#" class="text-red-400 hover:text-red-600" @click="confirmDeletesprint(sprint.id)">Delete</a>
                  </div>
                  </TableDataCell>
  
                </TableRow>
              </template>
            </Table>
          </div>
        </div>
      </AdminLayout>
      
      <!-- Modale de confirmation de suppression -->
      <Modal :show="showConfirmDeletesprintModal" @close="closeModal">
        <div class="p-6">
          <h2 class="text-lg font-semibold text-slate-800">Are you sure to delete this sprint?</h2>
          <div class="mt-6 flex space-x-4">
            <DangerButton @click="deletesprint">Delete</DangerButton>
            <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
          </div>
        </div>
      </Modal>
    </div>
  </template>
  
  <script setup>
  import AdminLayout from "@/Layouts/AdminLayout.vue";
  import { Head, Link,router } from "@inertiajs/vue3";
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
  sprints:Object,
  filters: Object,

});

const search = ref(props.filters.search);

watch(search, (value) => {
  Inertia.get(
    "/admin/sprints",
    { search: value},
    {
      preserveState: true,
      replace: true,
    }
  );
});

  
  const showConfirmDeletesprintModal = ref(false);
  const sprintId = ref(null); // Define sprintId ref
  
  const closeModal = () => {
    showConfirmDeletesprintModal.value = false;
  };
  
  const confirmDeletesprint = (id) => {
    showConfirmDeletesprintModal.value = true;
    sprintId.value = id; // Store the sprint ID in sprintId ref
  };
  
  
  const deletesprint = async () => {
  if (sprintId.value) {
    router.delete(route('admin.sprints.destroy', { id: sprintId.value }));

    showConfirmDeletesprintModal.value = false;
  }
};


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
  /* }.priority-cell {
  display: flex;
  align-items: center; 
} */
.priority-label {
  padding: 4px 8px;
  border-radius: 50%; /* Set border radius to 50% for a round shape */
  font-weight: bold;
  width: 40px; /* Adjust the width to control the size of the round label */
  height: 34px; /* Same as width to ensure a perfect circle */
  display: flex;
  justify-content: center;
  align-items: center;
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
    /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); Ajouter une ombre élégante */
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
  
  