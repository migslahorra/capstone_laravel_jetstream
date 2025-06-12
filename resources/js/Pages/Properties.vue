<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  properties: Array,
});
</script>

<template>
  <AppLayout title="Land Properties">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight justify-center">
          Uploaded Land Properties
        </h2>

        <template v-if="$page.props.auth.user && $page.props.auth.user.role === 'Seller'">
          <a
            href="/upload"
            class="ml-4"
            style="border: 1px solid lightgrey; padding: 10px 15px; display: inline-flex; align-items: center;"
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
        class="property-container mb-6 p-4 border rounded bg-white shadow-sm max-w-md mx-auto text-sm text-left"
      >
        <div class="mb-2">
          <i class="fa fa-home mr-1"></i><strong>Title:</strong> {{ property.title }}
        </div>
        <div class="mb-2">
          <i class="fa fa-info-circle mr-1"></i><strong>Description:</strong> {{ property.description }}
        </div>
        <div class="mb-2">
          <i class="fa fa-ruler-combined mr-1"></i><strong>Area:</strong> {{ property.area }} m²
        </div>
        <div class="mb-2">
          <i class="fa fa-map-marker-alt mr-1"></i><strong>Address:</strong> {{ property.address }}
        </div>
        <div class="mb-2">
          <i class="fa fa-money-bill mr-1"></i><strong>Price:</strong> ₱{{ property.price_range }}
        </div>
        <div class="mb-2">
          <i class="fa fa-check-circle mr-1"></i><strong>Status:</strong> {{ property.status }}
        </div>
        <div class="mt-2">
          <i class="fa fa-image mr-1"></i><strong>Images:</strong>
          <div class="flex gap-2 flex-wrap mt-1">
            <img
              v-for="image in JSON.parse(property.images)"
              :key="image"
              :src="`/storage/${image}`"
              alt="Property Image"
              class="rounded border"
              style="width: 80px; height: 60px; object-fit: cover;"
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
