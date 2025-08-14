<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  savedProperties: Array,
});

const savedProperties = ref([...props.savedProperties]);
const loading = ref(false);

function removeSaved(property) {
  loading.value = true;
  router.delete(`/saved/${property.id}`, {
    preserveScroll: true,
    onSuccess: () => {
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

function getImageArray(images) {
  if (Array.isArray(images)) return images;
  try {
    const parsed = JSON.parse(images);
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return typeof images === 'string' ? images.split(',') : [];
  }
}
</script>

<template>
  <AppLayout title="Saved Properties">
    <!-- Header -->
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-5 px-4 bg-gradient-to-r from-green-50 via-white to-blue-50 rounded-t-2xl shadow-sm border-b border-gray-200">
        <div class="flex items-center gap-3">
          <i class="fa-solid fa-bookmark text-green-600 text-3xl"></i>
          <h2 class="font-extrabold text-2xl text-green-700 tracking-tight">Saved Properties</h2>
        </div>
      </div>
    </template>

    <!-- Main Content -->
    <div class="py-10 px-4 bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-screen">
      <div v-if="!savedProperties.length" class="text-center text-gray-400 py-20 text-lg font-semibold">
        <i class="fa-regular fa-bookmark text-4xl mb-2"></i><br />No saved properties yet.
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        <div
          v-for="property in savedProperties"
          :key="property.id"
          class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-200 border border-green-100 flex flex-col"
        >
          <div class="p-6 flex-1">
            <!-- Status + ID -->
            <div class="flex items-center gap-2 mb-3">
              <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                {{ property.status }}
              </span>
              <span class="ml-auto text-xs text-gray-400">#{{ property.id }}</span>
            </div>

            <!-- Title + Description -->
            <h3 class="text-lg font-bold text-green-900 mb-1">{{ property.title }}</h3>
            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ property.description }}</p>

            <!-- Tags -->
            <div class="flex flex-wrap gap-2 mb-3">
              <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Area: {{ property.area }} m²</span>
              <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">₱{{ property.price_range }}</span>
            </div>

            <!-- Address -->
            <div class="text-gray-700 text-sm mb-4 flex items-center">
              <i class="fa-solid fa-location-dot mr-1 text-green-500"></i>
              {{ property.address }}
            </div>

            <!-- Images -->
            <div class="flex gap-2 flex-wrap">
              <img
                v-for="image in getImageArray(property.images)"
                :key="image"
                :src="`/storage/${image.trim()}`"
                alt="Property Image"
                class="rounded-lg border border-green-200 w-28 h-20 object-cover shadow"
              />
            </div>
          </div>

          <!-- Actions -->
          <div class="p-6 pt-0 flex flex-wrap gap-2 border-t border-gray-100">
            <button
              @click="goToConversation(property)"
              class="flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold rounded-lg shadow transition-colors duration-200"
            >
              <i class="fa-solid fa-comments"></i> Contact Owner
            </button>
            <button
              @click="removeSaved(property)"
              :disabled="loading"
              class="flex items-center gap-1 px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-semibold rounded-lg shadow transition-colors duration-200"
            >
              <i class="fa-solid fa-bookmark"></i> Remove Saved
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
