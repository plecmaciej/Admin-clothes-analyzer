<template>
  <Head title="Katalogi" />
  <div>
    <Toolbar />
    <div class="dashboard-layout">
      <Sidebar />

      <main class="dashboard-wrapper">
        <div class="welcome-message" v-if="authUser">
          Witaj {{ authUser.name }}
        </div>

        <section class="catalogs-list">
          <h3>Twoje katalogi</h3>

          <ul v-if="catalogs.length">
            <li v-for="catalog in catalogs" :key="catalog.id" class="catalog-item">
              <div class="catalog-header">
                <strong>{{ catalog.name }}</strong>
                <span>Ilość produktów: {{ catalog.products_count }}</span>
              </div>

              <div class="catalog-actions">
                <button @click="navigate(`/catalogs/${catalog.id}`)" class="view-button">
                  Zobacz katalog
                </button>

                <button @click="deleteCatalog(catalog.id)" class="delete-button">
                  Usuń katalog
                </button>
              </div>
            </li>
          </ul>

          <p v-else>Nie masz jeszcze żadnych katalogów.</p>
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

const authUser = usePage().props.authUser
const catalogs = usePage().props.catalogs || []

const navigate = (url) => {
  router.visit(url)
}

const deleteCatalog = (catalogId) => {
  if (confirm('Czy na pewno chcesz usunąć ten katalog?')) {
    router.delete(`/catalogs/${catalogId}`, {
      onSuccess: () => {
        router.visit(route('catalogs.index'), { method: 'get', preserveScroll: true })
      }
    })
  }
}
</script>
