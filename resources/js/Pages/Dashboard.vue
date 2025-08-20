<template>
  <Head title="Dashboard"/>
  <div>
    <Toolbar />

    <header class="page-header">
      <h2>Dashboard</h2>
    </header>

  <div class="dashboard-layout">

    <Sidebar/>

    <main class="dashboard-wrapper">
      <div class="welcome-message" v-if="authUser">
        Witaj {{ authUser.name }}
      </div>

      <section class="assigned-tasks">
        <h3>Twoje przypisane zadania</h3>
        <ul v-if="assignedTasks.length">
          <li v-for="task in assignedTasks" :key="task.id">
            <strong>{{ task.title }}</strong> – {{ getTranslatedStatus(task.status) }}
          </li>
        </ul>
        <p v-else>Nie masz jeszcze przypisanych zadań.</p>
      </section>
      
    </main>
    </div>
  </div>
</template>

<script setup>
import { router, usePage} from '@inertiajs/vue3'
import '../../css/dashboard.css'
import Toolbar from '@/Components/Toolbar.vue'
import Sidebar from '@/Components/Sidebar.vue'

function getTranslatedStatus(status) {
  const statusMap = {
    'assigned': 'Przypisane',
    'in_progress': 'W trakcie',
    'completed': 'Zakończone'
  };
  
  return statusMap[status] || status; 
}

const authUser = usePage().props.authUser
const assignedTasks = usePage().props.assignedTasks

</script>

