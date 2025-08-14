<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({ properties: Array, saved: Array });

const authUser = usePage().props.auth?.user;
const loading = ref(false);

const savedIds = ref(new Set(Array.isArray(props.saved) ? props.saved : []));

function isSaved(property) {
  return savedIds.value.has(property.id);
}

onMounted(async () => {
  try {
    const response = await axios.get('/saved-property-ids');
    savedIds.value = new Set(response.data.savedIds);
  } catch (err) {
    console.error('Failed to load saved property IDs', err);
  }
});

function goToConversation(property) {
  window.location.href = `/messages/property/${property.id}?presetMessage=${encodeURIComponent(
    'I am interested with this property (please continue to edit this message for your communication with the owner of the land property)'
  )}`;
}

function updateProperty(property) {
  window.location.href = `/properties/${property.id}/edit`;
}

function deleteProperty(property) {
  if (confirm('Are you sure you want to delete this property?')) {
    axios
      .delete(`/properties/${property.id}`)
      .then(() => {
        location.reload();
      })
      .catch(() => {
        alert('Failed to delete property.');
      });
  }
}

async function saveProperty(property) {
  loading.value = true;
  try {
    await axios.post('/saved', { property_id: property.id });
    savedIds.value.add(property.id);
  } catch (e) {
    alert('Failed to save property.');
  } finally {
    loading.value = false;
  }
}

async function removeSaved(property) {
  loading.value = true;
  try {
    await axios.delete(`/saved/${property.id}`);
    savedIds.value.delete(property.id);
  } catch (e) {
    alert('Failed to unsave property.');
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <AppLayout title="Land Properties">
    <!-- Header -->
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-5 px-4 bg-gradient-to-r from-green-50 via-white to-blue-50 rounded-t-2xl shadow-sm border-b border-gray-200">
        <div class="flex items-center gap-3">
          <img src="/images/landseek_logo.png" alt="LandSeek Logo" class="h-12 w-12 rounded-full border-2 border-green-500 bg-white shadow" />
          <h2 class="font-extrabold text-2xl text-green-700 tracking-tight">Properties</h2>
        </div>

        <!-- Upload button for sellers -->
        <template v-if="authUser && authUser.role === 'Seller'">
          <a
            href="/upload"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold shadow flex items-center gap-2 transition-colors duration-200"
          >
            <i class="fa-solid fa-upload"></i> Upload Property
          </a>
        </template>
      </div>
    </template>

    <!-- Main Content -->
    <div class="py-10 px-4 bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-screen">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        <div
          v-for="property in properties"
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
                v-for="image in JSON.parse(property.images)"
                :key="image"
                :src="`/storage/${image}`"
                alt="Property Image"
                class="rounded-lg border border-green-200 w-28 h-20 object-cover shadow"
              />
            </div>
          </div>

          <!-- Actions -->
          <div class="p-6 pt-0 flex flex-wrap gap-2 border-t border-gray-100">
            <template v-if="authUser && authUser.id === property.user_id">
              <button
                @click="updateProperty(property)"
                class="flex items-center gap-1 px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white font-semibold rounded-lg shadow transition-colors duration-200"
              >
                <i class="fa-solid fa-pen"></i> Update
              </button>
              <button
                @click="deleteProperty(property)"
                class="flex items-center gap-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow transition-colors duration-200"
              >
                <i class="fa-solid fa-trash"></i> Delete
              </button>
            </template>
            <template v-else>
              <button
                @click="goToConversation(property)"
                class="flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold rounded-lg shadow transition-colors duration-200"
              >
                <i class="fa-solid fa-comments"></i> Contact Owner
              </button>
              <button
                v-if="!isSaved(property)"
                @click="saveProperty(property)"
                :disabled="loading"
                class="flex items-center gap-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-green-800 font-semibold rounded-lg shadow transition-colors duration-200"
              >
                <i class="fa-solid fa-bookmark"></i> Save Property
              </button>
              <button
                v-else
                @click="removeSaved(property)"
                :disabled="loading"
                class="flex items-center gap-1 px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-semibold rounded-lg shadow transition-colors duration-200"
              >
                <i class="fa-solid fa-bookmark"></i> Saved Property
              </button>
            </template>
          </div>
        </div>
      </div>

      <!-- No results -->
      <div v-if="!properties.length" class="text-center text-gray-400 py-20 text-lg font-semibold">
        <i class="fa-regular fa-folder-open text-4xl mb-2"></i><br />No properties found.
      </div>
    </div>
  </AppLayout>
</template>
