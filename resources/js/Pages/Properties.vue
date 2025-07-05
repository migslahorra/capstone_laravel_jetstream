<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router, usePage } from '@inertiajs/vue3';



const props = defineProps({ properties: Array, saved: Array });
const authUser = usePage().props.auth.user;
import { ref } from 'vue';
const loading = ref(false);
// Ensure savedIds is always a ref to a Set, even if props.saved is missing or not an array

// Defensive: always initialize as a Set, even if props.saved is missing or not an array
const savedIds = ref(new Set(Array.isArray(props.saved) ? props.saved : []));

function isSaved(property) {
  return savedIds.value && savedIds.value.has(property.id);
}


function goToConversation(property) {
  router.get(`/messages/property/${property.id}`, {
    presetMessage: `I am interested with this property (please continue to edit this message for your communication with the owner of the land property)`,
  });
}

function updateProperty(property) {
  router.get(`/properties/${property.id}/edit`);
}

function deleteProperty(property) {
  if (confirm('Are you sure you want to delete this property?')) {
    router.delete(`/properties/${property.id}`);
  }
}




function saveProperty(property) {
  loading.value = true;
  router.post('/saved', { property_id: property.id }, {
    preserveScroll: true,
    onSuccess: () => {
      alert('Property saved!');
      loading.value = false;
      // Optionally, update UI state here if needed
    },
    onError: () => {
      alert('Failed to save property.');
      loading.value = false;
    }
  });
}




function removeSaved(property) {
  loading.value = true;
  router.delete(`/saved/${property.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      alert('Property unsaved!');
      loading.value = false;
      // Optionally, update UI state here if needed
    },
    onError: () => {
      alert('Failed to unsave property.');
      loading.value = false;
    }
  });
}
</script>

<template>
  <AppLayout title="Land Properties">
    <template #header>
      <div class="flex items-center justify-between py-4 px-2 bg-gradient-to-r from-green-600 to-green-400 rounded-t-lg shadow">
        <div class="flex items-center gap-2">
          <img src="/resources/image/landseek_logo.jpg" alt="LandSeek Logo" class="h-8 w-8 rounded-full border border-green-700 bg-white" />
          <h2 class="font-bold text-2xl text-white tracking-tight">Properties</h2>
        </div>
        <template v-if="authUser && authUser.role === 'Seller'">
          <a
            href="/upload"
            class="ml-4 bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg font-semibold shadow flex items-center transition"
          >
            <i class="fa-solid fa-upload mr-2"></i>Upload Property
          </a>
        </template>
      </div>
    </template>

    <div class="py-10 px-2 bg-white min-h-screen">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        <div
          v-for="property in properties"
          :key="property.id"
          class="bg-white rounded-2xl shadow-lg border border-green-100 hover:shadow-2xl transition p-6 flex flex-col justify-between"
        >
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">{{ property.status }}</span>
              <span class="ml-auto text-xs text-gray-400">#{{ property.id }}</span>
            </div>
            <div class="mb-3">
              <h3 class="text-lg font-bold text-green-900 mb-1">{{ property.title }}</h3>
              <p class="text-gray-700 text-sm">{{ property.description }}</p>
            </div>
            <div class="mb-3 flex flex-wrap gap-2">
              <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Area: {{ property.area }} m²</span>
              <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">₱{{ property.price_range }}</span>
            </div>
            <div class="mb-2 text-gray-700 text-sm"><i class="fa-solid fa-location-dot mr-1 text-green-500"></i>{{ property.address }}</div>
            <div class="mb-3">
              <div class="flex gap-2 flex-wrap mt-1">
                <img
                  v-for="image in JSON.parse(property.images)"
                  :key="image"
                  :src="`/storage/${image}`"
                  alt="Property Image"
                  class="rounded-lg border border-green-200 w-28 h-20 object-cover shadow"
                />
              </div>
            </div>
          </div>
          <div class="mt-4 flex gap-2 flex-wrap">
            <template v-if="authUser.id === property.user_id">
              <button
                @click="updateProperty(property)"
                class="flex items-center gap-1 px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white font-semibold rounded-lg shadow transition"
              >
                <i class="fa-solid fa-pen"></i>Update
              </button>
              <button
                @click="deleteProperty(property)"
                class="flex items-center gap-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow transition"
              >
                <i class="fa-solid fa-trash"></i>Delete
              </button>
            </template>
            <template v-else>
              <button
                @click="goToConversation(property)"
                class="flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold rounded-lg shadow transition"
              >
                <i class="fa-solid fa-comments"></i>Contact Owner
              </button>
            <button
              v-if="!isSaved(property)"
              @click="saveProperty(property)"
              :disabled="loading"
              class="flex items-center gap-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-green-800 font-semibold rounded-lg shadow transition"
            >
              <i class="fa-solid fa-bookmark"></i>Save Property
            </button>
            <button
              v-else-if="isSaved(property)"
              @click="removeSaved(property)"
              :disabled="loading"
              class="flex items-center gap-1 px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-semibold rounded-lg shadow transition"
            >
              <i class="fa-solid fa-bookmark"></i>Saved Property
            </button>
            </template>
          </div>
        </div>
      </div>
      <div v-if="!properties.length" class="text-center text-gray-400 py-20 text-lg font-semibold">
        <i class="fa-regular fa-folder-open text-4xl mb-2"></i><br />No properties found.
      </div>
    </div>
  </AppLayout>
</template>
