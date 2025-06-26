<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const property = page.props.property;
const images = page.props.images || [];
const user = page.props.auth.user;

function inquire() {
  router.post('/inquiries', {
    receiver_id: property.user_id,
    property_id: property.id,
  }, {
    onSuccess: () => {
      alert('Inquiry sent successfully!');
    },
    onError: () => {
      alert('Failed to send inquiry. Please check your input.');
    }
  });
}

function saveProperty() {
  router.post('/saved', {
    property_id: property.id,
  }, {
    onSuccess: () => {
      alert('Property saved!');
    },
    onError: () => {
      alert('Failed to save property. It may already be saved.');
    }
  });
}
</script>

<template>
  <AppLayout :title="property.title">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Property Details</h2>
    </template>

    <div class="py-6 px-4 max-w-4xl mx-auto bg-white shadow rounded">
      <h1 class="text-2xl font-bold mb-4">{{ property.title }}</h1>
      <p class="text-gray-700 mb-2"><strong>Price:</strong> {{ property.price_range }}</p>
      <p class="text-gray-700 mb-2"><strong>Area:</strong> {{ property.area }} sqm</p>
      <p class="text-gray-700 mb-2"><strong>Status:</strong> {{ property.status }}</p>
      <p class="text-gray-700 mb-2"><strong>Description:</strong> {{ property.description }}</p>
      <p class="text-gray-700 mb-2"><strong>Address:</strong> {{ property.address }}</p>
      <p class="text-gray-700 mb-2"><strong>City:</strong> {{ property.city }}, {{ property.province }}, {{ property.country }}</p>
      <p class="text-gray-700 mb-4"><strong>Postal Code:</strong> {{ property.postal_code || 'N/A' }}</p>

      <div v-if="images.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
        <div v-for="(image, index) in images" :key="index">
          <img :src="`/storage/${image}`" alt="Property Image" class="w-full h-48 object-cover rounded border" />
        </div>
      </div>

      <!-- Buttons for Buyers Only -->
      <div v-if="user && user.role === 'Buyer'" class="mt-4 flex gap-4">
        <button
          @click="inquire"
          class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          Inquire
        </button>
        <button
          @click="saveProperty"
          class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
        >
          Save Property
        </button>
      </div>
    </div>
  </AppLayout>
</template>
