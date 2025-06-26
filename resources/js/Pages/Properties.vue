<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({ properties: Array });
const authUser = usePage().props.auth.user;

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
</script>

<template>
  <AppLayout title="Land Properties">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Uploaded Land Properties</h2>
        <template v-if="authUser && authUser.role === 'Seller'">
          <a
            href="/upload"
            class="ml-4 border border-gray-300 px-4 py-2 rounded inline-flex items-center"
          >
            <i class="fa-solid fa-upload mr-2"></i>Upload Property
          </a>
        </template>
      </div>
    </template>

    <div class="mt-6">
      <div
        v-for="property in properties"
        :key="property.id"
        class="mb-6 p-4 border rounded bg-white shadow-sm max-w-md mx-auto text-sm text-left"
      >
        <div class="mb-2"><strong>Title:</strong> {{ property.title }}</div>
        <div class="mb-2"><strong>Description:</strong> {{ property.description }}</div>
        <div class="mb-2"><strong>Area:</strong> {{ property.area }} m²</div>
        <div class="mb-2"><strong>Address:</strong> {{ property.address }}</div>
        <div class="mb-2"><strong>Price:</strong> ₱{{ property.price_range }}</div>
        <div class="mb-2"><strong>Status:</strong> {{ property.status }}</div>
        <div class="mt-2">
          <strong>Images:</strong>
          <div class="flex gap-2 flex-wrap mt-1">
            <img
              v-for="image in JSON.parse(property.images)"
              :key="image"
              :src="`/storage/${image}`"
              alt="Property Image"
              class="rounded border w-20 h-15 object-cover"
            />
          </div>
        </div>
        <div class="mt-4 flex gap-2">
          <template v-if="authUser.id === property.user_id">
            <button
              @click="updateProperty(property)"
              class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600"
            >
              <i class="fa-solid fa-pen mr-1"></i>Update
            </button>
            <button
              @click="deleteProperty(property)"
              class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
            >
              <i class="fa-solid fa-trash mr-1"></i>Delete
            </button>
          </template>
          <template v-else>
            <button
              @click="goToConversation(property)"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              <i class="fa-solid fa-comments mr-1"></i>Contact Owner
            </button>
            <button
              class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400"
            >
              <i class="fa-solid fa-bookmark mr-1"></i>Save Property
            </button>
          </template>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
