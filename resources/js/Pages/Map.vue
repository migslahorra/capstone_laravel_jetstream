<script setup>
import { onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

onMounted(() => {
  // This is to make sure the leaflet javascript is loaded (window.L should exist)
  if (typeof window.L !== 'undefined') {
    // This initializes the map and set its view to Legazpi City
    const map = window.L.map('map').setView([13.1391, 123.7438], 13);

    // This is to add openStreetMap tiles as the base map layer
    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // This adds a marker at the center point (Legazpi City)
    const marker = window.L.marker([13.1391, 123.7438]).addTo(map);

    // This binds up a popup to the marker
    marker.bindPopup('<b>Hello world!</b><br>This is a Leaflet map.').openPopup();
  } else {
    console.error('Leaflet library not loaded!');
  }
});
</script>

<template>
  <AppLayout title="Map">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight justify-center">
          Map of Land Properties
        </h2>

        <template v-if="$page.props.auth.user && $page.props.auth.user.role === 'Buyer'">
          <select name="" id="" style="border: 1px solid lightgray;">
            <option value="Legazpi City">Legazpi City</option>
            <option value="Tabaco City">Tabaco City</option>
            <option value="Ligao City">Ligao City</option>
          </select>
        </template>
      </div>
    </template>

      <div id="map" style="height: 400px; width: 700px; margin: 2.5%; border: 2px solid lightgrey; border-radius: 5px;"></div>  

    <footer style="font-size: small; position: fixed; bottom: 0; text-align: center; width: 100%; margin-bottom: 15px;">
        <p>&copy; 2025 LandSeek. All Rights Reserved.</p>
    </footer>
  </AppLayout>
</template>
