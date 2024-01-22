<script setup>
  import { ref , onMounted} from 'vue';
  import { router} from "@inertiajs/vue3";
  import draggable from 'vuedraggable';
  import { EllipsisHorizontalIcon,PlusIcon ,PencilIcon,TrashIcon} from "@heroicons/vue/24/outline";
  import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
  import UserLayout from "@/Layouts/UserLayout.vue";
  import axios from 'axios';
  import { Inertia } from "@inertiajs/inertia";

const deleteTask = (taskId) => {
  if (confirm("Are you sure you want to delete this task?")) {
    Inertia.delete(route('users.tasks.destroy', taskId)).then(() => {
      // Handle any post-deletion logic, such as refreshing the task list
    });
  }
};

  const addTask = (taskName) => {
    axios.post('/tasks', { name: taskName })
        .then((response) => {
            console.log(response.data.message);
            const newTask = response.data.task;
        })
        .catch((error) => {
            console.error('Error adding task:', error);

            // Log the response data for debugging
            console.log(error.response.data);
        });
};





const { managedTasks } = defineProps(['managedTasks']);
const backlogs = ref([
  { name: 'To do', tasks: [] },
  { name: 'Not Started', tasks: [] },
  { name: 'In progress', tasks: [] },
  { name: 'In review', tasks: [] },
  { name: 'Completed', tasks: [] }
]); 
const showAddCardInput = ref(false);
const newTaskName = ref('');

const toggleAddCardInput = () => {
  showAddCardInput.value = !showAddCardInput.value;
  newTaskName.value = ''; 
};



const cancelAddTask = () => {
  toggleAddCardInput(); 
};

    
const handleDrop = (targetBacklogIndex, event) => {
  const sourceBacklogIndex = parseInt(event.from.getAttribute('data-backlog-index'), 10);
  const taskId = event.item.getAttribute('data-task-id');

  const sourceBacklog = backlogs.value[sourceBacklogIndex];
  const targetBacklog = backlogs.value[targetBacklogIndex];

  const taskToMove = sourceBacklog.tasks.find(task => task.id === taskId);

  if (taskToMove) {
    sourceBacklog.tasks = sourceBacklog.tasks.filter(task => task.id !== taskId);
    targetBacklog.tasks.push(taskToMove);

    // Update Local Storage
    localStorage.setItem('tasks', JSON.stringify(backlogs.value));
  }
};

const saveTask = () => {
  if (newTaskName.value.trim() !== '') {
    const activeBacklog = backlogs.value.find((backlog) => backlog.active);

    if (activeBacklog) {
      const newTask = createTask(newTaskName.value);
      activeBacklog.tasks.push(newTask);
      toggleAddCardInput();
      localStorage.setItem('tasks', JSON.stringify(backlogs.value));
    }
  }
};
const setActiveBacklog = (backlog) => {
  // Mark the clicked backlog as active
  backlogs.value.forEach((b) => {
    b.active = b === backlog;
  });
};



managedTasks.forEach(task => {
	const backlog = backlogs.value.find(backlog => backlog.name === task.status);
	if (backlog) {
	  backlog.tasks.push(task);
	}
  });
  const dragOptions = {
  group: 'backlogs',
  animation: 150,
  onAdd: (event) => {
    console.log('added', event);
    // const backlogIndex = parseInt(event.to.getAttribute('data-backlog-index'), 10);
    // const taskIndex = event.newIndex;
    // const task = event.from.dataset.taskId;

  }
};

const onTaskMoved = (evt) => {
  const url = route('users.tasks.update_status', { task: evt.draggedContext.element.id });
  axios.put(url, { status: evt.to.dataset.backlogName }).then((r) => {
    console.log(r);
  });
};

  



  
const statusColors = {
    'To do': 'bg-indigo-300',
    'Not Started': 'bg-red-100',
    'In progress': 'bg-yellow-100',
    'In review': 'bg-pink-100',
    'Completed': 'bg-green-100'
  };

</script>
<template>
  <UserLayout class="flex flex-col max-w-screen h-screen overflow-auto text-gray-700 bg-gradient-to-tr from-oceaned via-indigo-100 to-pink-200">
    <div class="flex flex-col max-w-screen h-screen overflow-auto ">
    <header >
      
    </header>

    <main class="flex-1 overflow-hidden">
      <div class="flex flex-col h-full">
        <div class="shrink-0 flex justify-between items-center p-4">
          <h1 class="text-2xl font-bold rounded-lg  py-2 mb-6 flex items-center h-6 px-3   text-pink-500 bg-pink-100 " >My tasks</h1>
        </div>
        
        <div class="flex-1 overflow-x-auto  ">
          <div class="inline-flex h-full items-start px-4 pb-4 space-x-4">
        <div
          v-for="(backlog, index) in backlogs"
          :key="index"
          :class="['w-72 rounded-md']"
          class="flex flex-col bg-white rounded-lg cursor-pointer"
          @click="setActiveBacklog(backlog)"

        >
          <div class="flex items-center justify-between px-3 py-2">
            <h1
              :class="[
                'text-sm font-light text-black rounded-md px-3 py-1 mb-4',
                statusColors[backlog.name],
                'drag-handle'
              ]"
            >
              {{ backlog.name }}
            </h1>

                  <!-- ... your Menu code ... -->
                <Menu as="div" class="relative z-10">
                  <MenuButton class="hover:bg-gray-300 w-8 h-8 rounded-md grid place-content-center">
                    <EllipsisHorizontalIcon class="text-blue-800 w-5 h-5" />
                  </MenuButton>

                  <transition
                    enter-active-class="transition transform duration-100 ease-out"
                    enter-from-class="opacity-0 scale-90"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition transform duration-100 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-90"
                  >
                    
                  </transition>
                </Menu>
              </div>
              <div class="pb-3 flex flex-col overflow-hidden ">
                <div class="px-3 flex-1 overflow-y-auto  ">
                  <ul class="space-y-3">
                    <draggable
                     v-model="backlog.tasks"
  class="drag-container"
  group="tasks"
  :options="dragOptions"
  :data-backlog-index="index"
  :data-backlog-name="backlog.name"
  @end="handleDrop(index, $event)"
  :itemKey="task => task.id" 
  :move="onTaskMoved"
>
  <template #item="{ element }">
    <li
      :key="element.id"
      class="group relative bg-gray-100 p-3 shadow rounded-md border-b border-gray-300 hover:bg-gray-50 drag-handle"
    >
      <a :href="'/tasks/' + (element.id || '')" class="text-sm">{{ element.name }}</a>
      <button class="hidden absolute top-1 right-1 w-8 h-8 bg-gray-50 group-hover:grid place-content-center rounded-md text-gray-600 hover:text-black hover:bg-gray-200">
        <a :href="route('users.tasks.edit', { id: element.id })">
  <PencilIcon class="w-5 h-5" />
</a>
                      </button>
                       <!-- Add the TrashIcon here -->
    <button class="hidden absolute top-1 right-10 w-8 h-8 bg-gray-50 group-hover:grid place-content-center rounded-md text-gray-600 hover:text-black hover:bg-gray-200">
      <!-- Trigger a delete request when this button is clicked -->
      <TrashIcon class="w-5 h-5" @click="deleteTask(element.id)" />
    </button>
                    

                      
                      
    </li>
    
  </template>
  
 
</draggable>

            </ul>
                 
                </div>

<!--                <div v-if="showAddCardInput" class="px-3 mt-3">
    <input v-model="newTaskName" @keyup.enter="saveTask" class="p-2 border rounded-md" placeholder="Enter task name" />
    <div class="mt-2">
      <button @click="saveTask" class="px-4 py-2 bg-green-200 text-black rounded-md">Save</button>
      <button @click="cancelAddTask" class="px-4 py-2 bg-red-200 text-black rounded-md ml-2">Cancel</button>
    </div>
  </div>
  <div v-else class="px-3 mt-3">
  <div class="flex items-center p-2 text-sm font-medium text-gray-600 hover:text-black hover:bg-gray-300 w-full rounded-md">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
    </svg>
    <input v-model="newTaskName" placeholder="Enter task name" />
    <button @click="addTask(newTaskName)">Create</button>
  </div>
</div>
-->

      <div class="px-3 mt-3">
  <a href="/users/tasks/create" class="flex items-center p-2 text-sm font-medium text-gray-600 hover:text-black hover:bg-gray-300 w-full rounded-md">
    <PlusIcon class="h-5 w-5"></PlusIcon>
    <span class="ml-1">Add card</span>
  </a>
</div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  </UserLayout>
</template>