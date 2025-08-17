<template>
  <Head title="Wszystkie zadania" />
  <div>
    <Toolbar />

    <div class="dashboard-layout">
      <Sidebar />

      <main class="dashboard-wrapper">
        <div class="welcome-message" v-if="authUser">
          Witaj {{ authUser.name }}
        </div>

        <section class="task-list">
          <h3>Wszystkie zadania</h3>
          <ul v-if="tasks.length">
            <li v-for="task in tasks" :key="task.id" class="task-item">
              <div class="task-header">
                <strong>{{ task.title }}</strong> – <em>{{ task.status }}</em>
                <p>Autor: {{ task.author.name }}</p> 
                <p v-if="task.assignee">Przypisane do: {{ task.assignee.name }}</p>
              </div>
              
              <p>{{ task.description }}</p>

              <div class="assign-section">
                <div  v-if="task.status === 'open'">
                  <button
                    :disabled="assignedTaskIds.includes(task.id)"
                    @click="assignTask(task.id)"
                    class="assign-button"
                  >
                    {{ assignedTaskIds.includes(task.id) ? 'Przypisano' : 'Przypisz do siebie' }}
                  </button>
                </div>

                <div v-if="task.assignee_id === authUser.id">
                  <span class="assigned-info">To Twoje zadanie</span>
                </div>

                <button
                  v-if="task.author.id === authUser.id"
                  @click="deleteTask(task.id)"
                  class="delete-button"
                >
                  Usuń
                </button>
              </div>
              


            </li>
          </ul>

          <p v-else>Brak dostępnych zadań.</p>
        </section>

        <button @click="showForm = !showForm" class="form-button">
          {{ showForm ? 'Ukryj formularz' : '+ Dodaj nowe zadanie' }}
        </button>

        <section class="task-form" v-if="showForm">
          <h3>Utwórz nowe zadanie</h3>
          <form @submit.prevent="createTask">
            <div class="form-group">
              <label>Tytuł:</label>
              <input v-model="newTask.title" required />
            </div>
            <div class="form-group">
              <label>Opis:</label>
              <textarea v-model="newTask.description"></textarea>
            </div>
            <button type="submit" class="assign-button">Dodaj zadanie</button>
          </form>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { usePage, router,Head } from '@inertiajs/vue3'
import { reactive, ref, computed  } from 'vue'
import Toolbar from '@/Components/Toolbar.vue'
import Sidebar from '@/Components/Sidebar.vue'
import '../../css/dashboard.css'

console.log('Aktualny komponent:', usePage().component)
console.log('Props:', usePage().props)

const authUser = usePage().props.authUser
const tasks = usePage().props.tasks || []

const assignedTaskIds = ref(tasks.filter(t => t.assignee_id === authUser.id).map(t => t.id))

const assignTask = (taskId) => {
  router.post(`/all-tasks/${taskId}/assign`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      assignedTaskIds.value.push(taskId)
    }
  })
}

const newTask = ref({ title: '', description: '' })
const showForm = ref(false)

const createTask = () => {
  router.post('/all-tasks', newTask.value, {
    onSuccess: () => {
      router.visit(route('all-tasks.index'), {
        method: 'get',
        only: ['tasks'],
        preserveScroll: true
      })
    }
  })
}

const deleteTask = (taskId) => {
  if (confirm('Czy na pewno chcesz usunąć to zadanie?')) {
    router.delete(`/all-tasks/${taskId}`, {
      preserveScroll: true,
      onSuccess: () => {
        router.visit(route('all-tasks.index'), {
          method: 'get',
          only: ['tasks'],
          preserveScroll: true
        })
      }
    })
  }
}


</script>
