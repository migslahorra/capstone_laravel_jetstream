<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const properties = ref([]);

onMounted(async () => {
  // Fetch map data from Laravel endpoint
  const res = await fetch('/map-data');
  const data = await res.json();
  properties.value = data;

  if (typeof window.L !== 'undefined') {
    const map = window.L.map('map').setView([13.1391, 123.7438], 13);

    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const marker = window.L.marker([13.1391, 123.7438]).addTo(map);
    marker.bindPopup('<b>Legazpi City</b><br>You are currently viewing <br> in the city of Legazpi.').openPopup();

    // Dynamically add markers for each property
    properties.value.forEach((property) => {
      if (property.latitude && property.longitude) {
        const propertyMarker = window.L.marker([property.latitude, property.longitude]).addTo(map);
        propertyMarker.bindPopup(
          `<strong>${property.title}</strong><br>${property.address}<br><a href="/properties/${property.id}">View Details</a>`
        );
      }
    });
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

    <div id="map" style="height: 400px; width: 700px; margin-top: 2.5%; margin-left: 23%; border: 2px solid lightgrey; border-radius: 5px;"></div>  
  </AppLayout>
</template>