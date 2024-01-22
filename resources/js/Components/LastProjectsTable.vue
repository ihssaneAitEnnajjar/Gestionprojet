
<template>
    <div>
      
  
        <div class="max-w-7xl mx-auto py-4">
            <h1 class="text-2xl font-bold rounded-lg flex items-center h-6 text-sky-200" >My   Last Projects</h1>
          <div class="mt-6">
          
            <Table>
              <template #header>
                <TableRow>
                  <TableHeaderCell>ID</TableHeaderCell>
                  <TableHeaderCell>Project Name</TableHeaderCell>
                  <TableHeaderCell>Due date</TableHeaderCell>
                  <TableHeaderCell>Status</TableHeaderCell>
                  <TableHeaderCell>Action</TableHeaderCell>
                </TableRow>
              </template>
              <template #default>
                <TableRow v-for="project in lastProjects" :key="project.id" class="border-b">
                 <TableDataCell>{{ project.id }}</TableDataCell>
              <TableDataCell>{{ project.name }}</TableDataCell>
              <TableDataCell>{{ project.due_date }}</TableDataCell>
                  <TableDataCell>
                    <input type="text" class="status-input " v-model="project.status" readonly :class="{'bg-green-400 text-black':project.status=='Done','bg-sky-300':project.status=='In progress','bg-rose-400 text-black':project.status=='Not Started','bg-red-500 text-black':project.status=='Cancel'}"/>
                  </TableDataCell>
                  <TableDataCell class="space-x-4 action-column" >
                    <div class="action-wrapper">
                    <a href="#" class="text-red-400 hover:text-red-600" @click="confirmDeleteproject(project.id)">Delete</a>
                  </div>
                  </TableDataCell>
  
                </TableRow>
              </template>
            </Table>
          </div>
        </div>
      
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
  import { Head, Link, router } from "@inertiajs/vue3";
  import Table from "@/Components/Table.vue";
  import TableRow from "@/Components/TableRow.vue";
  import TableHeaderCell from "@/Components/TableHeaderCell.vue";
  import TableDataCell from "@/Components/TableDataCell.vue";
  import Modal from "@/Components/Modal.vue";
  import DangerButton from "@/Components/DangerButton.vue";
  import SecondaryButton from "@/Components/SecondaryButton.vue";
  import axios from 'axios';

  import { ref, onMounted } from 'vue';



const lastProjects = ref([]);


const fetchLastProjects = async () => {
  try {
    const response = await axios.get('/api/get-last-projects');
    lastProjects.value = response.data;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

onMounted(() => {
  fetchLastProjects();
});


onMounted(() => {
  fetchLastProjects();
});
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
    router.delete(route('users.projects.destroy', { id: projectId.value }));

    showConfirmDeleteprojectModal.value = false;
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
  .action-column {
    box-shadow: none; /* Ajouter une ombre élégante */
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
  
  