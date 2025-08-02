<template>
  <Toolbar/>
  <div class="login-container">
    <h1>Logowanie</h1>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" />
        <div v-if="form.errors.email" class="error">{{ form.errors.email }}</div>
      </div>

      <div class="form-group">
        <label>Hasło</label>
        <input v-model="form.password" type="password" />
        <div v-if="form.errors.password" class="error">{{ form.errors.password }}</div>
      </div>

      <button type="submit">Zaloguj</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import '../../../css/login.css' 
import Toolbar from '@/Components/Toolbar.vue'

const form = useForm({
  email: '',
  password: '',
})

function submit() {
  form.post('/login')
}
</script>

