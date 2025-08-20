<template>
  <Head :title="`Katalog: ${catalog.name}`" />
  <div>
    <Toolbar />
    <div class="dashboard-layout">
      <Sidebar />

      <main class="dashboard-wrapper">
        <div class="welcome-message" v-if="authUser">
          Witaj {{ authUser.name }}
        </div>

        <section class="catalog-products">
          <h3>Katalog: {{ catalog.title }}</h3>

          <ul v-if="catalog.products.length" class="product-grid">
            <li v-for="product in catalog.products" :key="product.id" class="product-item">
                <img 
                  :src="`/storage/${product.image_path}`" 
                  :alt="product.name" 
                  class="product-image"
                />
                <div class="product-header">
                  <strong>{{ product.name }}</strong>
                  <span>{{ product.pivot.price }} PLN</span>
                </div>

              <button @click="deleteProduct(product.id)" class="delete-button">
                Usuń produkt
              </button>
            </li>
          </ul>

          <p v-else>Ten katalog nie zawiera jeszcze produktów.</p>

          <section class="add-product">
            <h4>Dodaj nowy produkt</h4>
            <form @submit.prevent="addProduct">
              <input v-model="newProduct.name" placeholder="Nazwa produktu" required />
              <input v-model="newProduct.price" type="number" step="0.01" placeholder="Cena" required />
              <button type="submit">Dodaj</button>
            </form>
          </section>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3'
import Toolbar from '@/Components/Toolbar.vue'
import Sidebar from '@/Components/Sidebar.vue'
import { reactive } from 'vue'
import '../../css/catalog.css'

const authUser = usePage().props.authUser
const catalog = usePage().props.catalog || { products: [] }

const newProduct = reactive({
  name: '',
  price: '',
})

const deleteProduct = (productId) => {
  if (confirm('Czy na pewno chcesz usunąć produkt?')) {
    router.delete(`/catalogs/${catalog.id}/products/${productId}`, {
      onSuccess: () => {
        router.visit(route('catalogs.show', catalog.id), { method: 'get', preserveScroll: true })
      }
    })
  }
}

const addProduct = () => {
  router.post(`/catalogs/${catalog.id}/products`, newProduct, {
    onSuccess: () => {
      newProduct.name = ''
      newProduct.price = ''
      router.visit(route('catalogs.show', catalog.id), { method: 'get', preserveScroll: true })
    }
  })
}
</script>
