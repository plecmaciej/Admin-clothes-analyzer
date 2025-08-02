<template>
  <Toolbar />
  <div class="login-container">
    <h1>Rejestracja</h1>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label>Imię</label>
        <input v-model="form.name" type="text" />
        <div v-if="form.errors.name" class="error">{{ form.errors.name }}</div>
      </div>

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

      <div class="form-group">
        <label>Potwierdź hasło</label>
        <input v-model="form.password_confirmation" type="password" />
        <div v-if="form.errors.password_confirmation" class="error">{{ form.errors.password_confirmation }}</div>
      </div>

      <button type="submit">Zarejestruj</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import '../../../css/login.css' // może być ta sama
import Toolbar from '@/Components/Toolbar.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

function submit() {
  form.post('/register')
}
</script>
