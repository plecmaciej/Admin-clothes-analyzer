<template>
  <Head title="Twoje zadania" />
  <div>
    <Toolbar />

    <div class="dashboard-layout">
      <Sidebar />

      <main class="dashboard-wrapper">
        <div class="welcome-message" v-if="authUser">
          Witaj {{ authUser.name }}
        </div>

        <section class="assigned-tasks">
          <h3>Twoje przypisane zadania</h3>

          <ul v-if="tasks.length">
            <li v-for="task in tasks" :key="task.id" class="task-item">
              <div class="task-header">
                <strong>{{ task.title }}</strong>
                <p>Autor: {{ task.author.name }}</p>
                <span class="status">Status: {{ task.status }}</span>
              </div>

              <div class="task-form">
                <form v-if="task.status === 'assigned'" @submit.prevent="submit(task.id)">
                  <div class="form-group">
                    <label>Typ:</label>
                    <select v-model="form[task.id].type" required>
                      <option value="">Wybierz...</option>
                      <option value="one_time">Jednorazowe</option>
                      <option value="periodic">Okresowe</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Target URL:</label>
                    <input v-model="form[task.id].target_url" type="text" />
                  </div>

                  <div v-if="form[task.id].type === 'periodic'">
                    <div class="form-group">
                      <label>Start:</label>
                      <input type="datetime-local" v-model="form[task.id].start_date" />
                    </div>
                    <div class="form-group">
                      <label>Koniec:</label>
                      <input type="datetime-local" v-model="form[task.id].end_date" />
                    </div>
                    <div class="form-group">
                      <label>Częstotliwość:</label>
                      <input v-model="form[task.id].frequency" placeholder="np. daily, weekly" />
                    </div>
                  </div>

                  <button type="submit" class="assign-button">Zapisz</button>
                  <button @click="deleteTask(task.id)" class="delete-button">
                    Usuń zadanie
                  </button>
                </form>

                <div v-else>
                  <div class="readonly-group">
                    <p><strong>Typ:</strong> {{ form[task.id].type }}</p>
                    <p><strong>Target URL:</strong> {{ form[task.id].target_url }}</p>

                    <div v-if="form[task.id].type === 'periodic'">
                      <p><strong>Start:</strong> {{ form[task.id].start_date }}</p>
                      <p><strong>Koniec:</strong> {{ form[task.id].end_date }}</p>
                      <p><strong>Częstotliwość:</strong> {{ form[task.id].frequency }}</p>
                    </div>
                    <button @click="deleteTask(task.id)" class="delete-button2">
                      Usuń zadanie
                    </button>
                  </div>
                </div>
              </div>
            </li>
          </ul>

          <p v-else>Nie masz przypisanych zadań.</p>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3'
import Toolbar from '@/Components/Toolbar.vue'
import Sidebar from '@/Components/Sidebar.vue'
import '../../css/dashboard.css'
import { reactive } from 'vue'

const authUser = usePage().props.authUser
const tasks = usePage().props.assignedTasks || []

const form = reactive({})

tasks.forEach(task => {
  form[task.id] = {
    type: task.type || '',
    target_url: task.target_url || '',
    start_date: task.start_date || '',
    end_date: task.end_date || '',
    frequency: task.frequency || '',
  }
})

const deleteTask = (taskId) => {
  if (confirm('Czy na pewno chcesz usunąć to zadanie?')) {
    router.delete(`/your-tasks/${taskId}`, {
      onSuccess: () => {
        router.visit(route('your-tasks.index'), {
          method: 'get',
          only: ['tasks'],
          preserveScroll: true
        })
      }
    })
  }
}

const submit = (taskId) => {
  router.patch(`/your-tasks/${taskId}`, form[taskId], {
    preserveScroll: true,
    onSuccess: () => {
        router.visit(route('your-tasks.index'), {
          method: 'get',
          only: ['tasks'],
          preserveScroll: true
        })
    }
  })
}
</script>
