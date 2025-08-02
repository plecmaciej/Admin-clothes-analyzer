<template>
  <nav class="toolbar">
    <div class="toolbar-left">
      <a href="/">
        <img src="/images/logo.png" alt="Logo" class="logo" />
      </a>
    </div>


    <div class="toolbar-right">
        <div class="mode" v-if="user">
          <button class="theme-toggle" @click="goToDashboard">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span>Panel</span>
          </button>
        </div>

        <div class="mode">
          <button class="theme-toggle" @click="toggleDarkMode">
            <svg
              v-if="!isDark"
              xmlns="http://www.w3.org/2000/svg" class="icon" fill="none"  viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" >
              <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>

            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>

            <span >{{ isDark ? 'Jasny' : 'Ciemny' }}</span>
          </button>

        </div>

        <div class="mode" v-if="user" @mouseenter="showDropdown = true" @mouseleave="showDropdown = false">
          <button class="theme-toggle">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <span>{{ user.name }}</span>
            <span class="arrow"> {{ showDropdown ? '˄' : '˅' }}</span>
          </button>

          <div class="dropdown-menu" v-if="showDropdown">
            <a href="/dashboard">Panel</a>
            <Link href="/logout" method="post" as="button" class="logout-button">Wyloguj</Link>
          </div>

       </div>      

        <div class="mode" v-else @mouseenter="showDropdown = true" @mouseleave="showDropdown = false">
          <button class="theme-toggle">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <span>Zaloguj</span>
            <span>się</span>
          </button>
          <div class="dropdown-menu" v-if="showDropdown">
            <a href="/login">Zaloguj</a>
            <a href="/register">Zarejestruj</a>
          </div>
        </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import '../../css/toolbar.css'
import { usePage, Link } from '@inertiajs/vue3'

const user = usePage().props.auth?.user
const isDark = ref(localStorage.getItem('darkMode') === 'enabled')
const showDropdown = ref(false)


onMounted(() => {
  document.body.classList.toggle('dark-mode', isDark.value)
})

function toggleDarkMode() {
  isDark.value = !isDark.value
  document.body.classList.toggle('dark-mode', isDark.value)
  localStorage.setItem('darkMode', isDark.value ? 'enabled' : 'disabled')
}

function goToDashboard() {
  window.location.href = '/dashboard'
}
</script>