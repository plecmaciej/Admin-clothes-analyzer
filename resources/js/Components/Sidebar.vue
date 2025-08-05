<template>
  <aside class="sidebar">
    <nav class="sidebar-nav">
      <button @click="navigate('/dashboard')">Podsumowanie</button>  
      <button @click="navigate('/your-tasks')">Twoje zadania</button>
      <button @click="navigate('/all-tasks')">Zadania</button>
      <button @click="navigate('/biblioteki')">Biblioteki</button>
      <button @click="navigate('/wykresy')">Wykresy</button>
      <button @click="navigate('/informacje')">Informacje</button>
      <button @click="navigate('/ustawienia')">Ustawienia</button>
    </nav>
  </aside>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import '../../css/sidebar.css';


function goToYourTasks() {
  router.visit('/your-tasks')
}

function navigate(path) {
  router.visit(path)
}
</script>