<template>
  <Head title="Dashboard"/>
  <div>
    <Toolbar />

    <header class="page-header">
      <h2>Dashboard</h2>
    </header>

    <main class="dashboard-wrapper">
      <div class="welcome-message" v-if="authUser">
        Witaj {{ authUser.name }}
      </div>

      <nav class="tabs">
        <button class="tab">Analizator</button>
        <button class="tab">Pobierz bibliotekę ubrań</button>
        <button class="tab">Podsumowanie</button>
      </nav>

      <section class="content-box">
        <p>Wybierz zakładkę, aby rozpocząć.</p>
      </section>
    </main>
  </div>
</template>

<script setup>
import { router, usePage} from '@inertiajs/vue3'
import '../../css/dashboard.css'
import Toolbar from '@/Components/Toolbar.vue'

const authUser = usePage().props.authUser
</script>

