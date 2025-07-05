
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const page = usePage();
let savedPropertiesRaw = page.props.savedProperties || [];
if (savedPropertiesRaw.length && savedPropertiesRaw[0] && savedPropertiesRaw[0].hasOwnProperty('property')) {
  savedPropertiesRaw = savedPropertiesRaw.map(sp => sp.property).filter(Boolean);
}
const savedProperties = ref(savedPropertiesRaw);
const authUser = page.props.auth.user;
const loading = ref(false);


function removeSaved(property) {
  loading.value = true;
  router.delete(`/saved/${property.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      alert('Property unsaved!');
      savedProperties.value = savedProperties.value.filter(p => p.id !== property.id);
      loading.value = false;
    },
    onError: () => {
      alert('Failed to unsave property.');
      loading.value = false;
    }
  });
}

function goToConversation(property) {
  router.get(`/messages/property/${property.id}`, {
    presetMessage: `I am interested with this property (please continue to edit this message for your communication with the owner of the land property)`,
  });
}
</script>

<template>
  <AppLayout title="Saved Properties">
    <template #header>
      <div class="flex items-center justify-between py-4 px-2 bg-gradient-to-r from-green-600 to-green-400 rounded-t-lg shadow">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-bookmark text-white text-2xl"></i>
          <h2 class="font-bold text-2xl text-white tracking-tight">Saved Properties</h2>
        </div>
      </div>
    </template>

    <div class="py-10 px-2 bg-white min-h-screen">
      <div v-if="!savedProperties.length" class="text-center py-20">
        <div class="inline-block bg-white px-8 py-6 rounded-lg shadow border border-green-200">
          <i class="fa-regular fa-bookmark text-4xl text-gray-400 mb-4"></i>
          <p class="text-lg text-gray-700 font-semibold">No saved properties yet.</p>
          <p class="text-gray-500">You haven't saved any properties.</p>
        </div>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        <div
          v-for="property in savedProperties"
          :key="property.id"
          class="bg-white rounded-2xl shadow-lg border border-green-100 hover:shadow-2xl transition p-6 flex flex-col justify-between"
        >
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">{{ property?.status }}</span>
              <span class="ml-auto text-xs text-gray-400">#{{ property?.id }}</span>
            </div>
            <div class="mb-3">
              <h3 class="text-lg font-bold text-green-900 mb-1">{{ property?.title }}</h3>
              <p class="text-gray-700 text-sm">{{ property?.description }}</p>
            </div>
            <div class="mb-3 flex flex-wrap gap-2">
              <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Area: {{ property?.area }} m²</span>
              <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">₱{{ property?.price_range }}</span>
            </div>
            <div class="mb-2 text-gray-700 text-sm"><i class="fa-solid fa-location-dot mr-1 text-green-500"></i>{{ property?.address }}</div>
            <div class="mb-3">
              <div class="flex gap-2 flex-wrap mt-1">
                <img
                  v-for="image in (property?.images ? JSON.parse(property.images) : [])"
                  :key="image"
                  :src="`/storage/${image}`"
                  alt="Property Image"
                  class="rounded-lg border border-green-200 w-28 h-20 object-cover shadow"
                />
              </div>
            </div>
          </div>
          <div class="mt-4 flex gap-2 flex-wrap">
            <button
              @click="goToConversation(property)"
              class="flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold rounded-lg shadow transition"
            >
              <i class="fa-solid fa-comments"></i>Contact Owner
            </button>
            <button
              @click="removeSaved(property)"
              :disabled="loading"
              class="flex items-center gap-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-green-800 font-semibold rounded-lg shadow transition"
            >
              <i class="fa-solid fa-bookmark"></i>Remove Saved
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
