<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import LastProjectsTable from '@/Components/LastProjectsTable.vue';



import {
    Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  RadialLinearScale,
  ArcElement,
 } from 'chart.js'
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  RadialLinearScale,
  ArcElement,
);
import { Line, PolarArea } from 'vue-chartjs';


defineProps({ tasksPerMonth: Object, tasksByStatus: Object });

const chartOptions = {
    responsive: true
}
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            
        <div class="px-6 pt-6 2xl:container">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2 lg:col-span-1" >
                    <div class="h-full py-8 px-6 space-y-6 rounded-xl border border-gray-200 bg-white">
                        
                        <Line :data="tasksPerMonth" :options="chartOptions" />

                        <div>
                            <h5 class="text-xl text-gray-600 text-center">Tasks Per Month</h5>
                        </div>
                    </div>
                </div>
    
                <div>
                    <div class="lg:h-full py-8 px-6 text-gray-600 rounded-xl border border-gray-200 bg-white">
                        <PolarArea :data="tasksByStatus" :options="chartOptions" />  
                        <h5 class="text-xl text-gray-700 text-center">TAsk to customize</h5>

                        
                    </div>
                </div>
            </div>
        </div>
        <LastProjectsTable :lastProject="lastProject" />

    </div>
    </AdminLayout>

</template>
