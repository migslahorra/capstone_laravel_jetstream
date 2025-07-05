<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const properties = ref([]);
const mapRef = ref(null);
const selectedCity = ref('Legazpi City');

const cityCoords = {
  'Legazpi City': [13.1391, 123.7438],
  'Tabaco City': [13.3587, 123.7337],
  'Ligao City': [13.2222, 123.5144],
};

function moveToCity(city) {
  selectedCity.value = city;
  if (mapRef.value && cityCoords[city]) {
    mapRef.value.setView(cityCoords[city], 13);
  }
}

onMounted(async () => {
  // Fetch map data from Laravel endpoint
  const res = await fetch('/map-data');
  const data = await res.json();
  properties.value = data;

  if (typeof window.L !== 'undefined') {
    mapRef.value = window.L.map('map').setView(cityCoords[selectedCity.value], 13);

    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(mapRef.value);

    // Center marker for initial city
    const marker = window.L.marker(cityCoords[selectedCity.value]).addTo(mapRef.value);
    marker.bindPopup(`<b>${selectedCity.value}</b><br>You are currently viewing <br> in the city of ${selectedCity.value}.`).openPopup();

    // Dynamically add markers for each property
    properties.value.forEach((property) => {
      if (property.latitude && property.longitude) {
        const propertyMarker = window.L.marker([property.latitude, property.longitude]).addTo(mapRef.value);
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
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h2 class="font-extrabold text-2xl text-green-700 tracking-tight">Map of Land Properties</h2>
        <template v-if="$page.props.auth.user && $page.props.auth.user.role === 'Buyer'">
          <select
            v-model="selectedCity"
            @change="moveToCity(selectedCity)"
            class="border border-green-300 rounded-lg px-4 py-2 text-green-700 font-semibold focus:ring-2 focus:ring-green-400 focus:outline-none shadow"
          >
            <option value="Legazpi City">Legazpi City</option>
            <option value="Tabaco City">Tabaco City</option>
            <option value="Ligao City">Ligao City</option>
          </select>
        </template>
      </div>
    </template>

    <div
      id="map"
      style="height: 400px; width: 700px; margin-top: 2.5%; margin-left: 23%; border: 2px solid #4ade80; border-radius: 12px; box-shadow: 0 4px 24px 0 rgba(16, 185, 129, 0.08);"
      class="bg-white"
    ></div>
  </AppLayout>
</template>