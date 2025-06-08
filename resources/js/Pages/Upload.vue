<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  title: '',
  description: '',
  price_range: '',
  area: '',
  address: '',
  street: '',
  purok: '',
  city: '',
  province: '',
  country: '',
  postal_code: '',
  latitude: '',
  longitude: '',
  status: 'available',
  images: []
});

let map = ref(null);
let marker = ref(null);

const handleFileChange = (e) => {
  form.images = Array.from(e.target.files);
};

onMounted(() => {
  // Load Leaflet CSS
  if (!document.getElementById('leaflet-css')) {
    const leafletCss = document.createElement('link');
    leafletCss.id = 'leaflet-css';
    leafletCss.rel = 'stylesheet';
    leafletCss.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(leafletCss);
  }

  // Load Leaflet JS
  if (typeof window.L === 'undefined') {
    const leafletScript = document.createElement('script');
    leafletScript.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    leafletScript.onload = initMap;
    document.body.appendChild(leafletScript);
  } else {
    initMap();
  }
});

function initMap() {
  const mapContainer = document.getElementById('map');
  if (!mapContainer) return;

  // Initialize map centered on Legazpi City
  map.value = window.L.map('map').setView([13.1391, 123.7438], 13);

  // Add OpenStreetMap tiles
  window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map.value);

  // Add click event to place marker and get coordinates
  map.value.on('click', (e) => {
    const { lat, lng } = e.latlng;
    
    // Remove existing marker if any
    if (marker.value) {
      map.value.removeLayer(marker.value);
    }

    // Add new marker at clicked location
    marker.value = window.L.marker([lat, lng], { 
      draggable: true 
    }).addTo(map.value);
    
    // Update form coordinates
    form.latitude = lat.toFixed(6);
    form.longitude = lng.toFixed(6);

    // Update coordinates when marker is dragged
    marker.value.on('dragend', (e) => {
      const newPos = e.target.getLatLng();
      form.latitude = newPos.lat.toFixed(6);
      form.longitude = newPos.lng.toFixed(6);
    });

    marker.value.bindPopup(`
      <b>Selected Location</b><br>
      Latitude: ${lat.toFixed(6)}<br>
      Longitude: ${lng.toFixed(6)}
    `).openPopup();
  });
}
</script>

<template>
  <AppLayout title="Add Property">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Add New Property
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="form.post('/properties')" class="bg-white p-6 rounded shadow space-y-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Property Details -->
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input 
                  v-model="form.title" 
                  type="text" 
                  class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                  required 
                  placeholder="Property title"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea 
                  v-model="form.description" 
                  class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                  rows="4" 
                  required
                  placeholder="Detailed description of the property"
                ></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Price Range *</label>
                  <input 
                    v-model="form.price_range" 
                    type="text" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                    required 
                    placeholder="e.g. ₱500,000 - ₱1,000,000"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Area (sq.m) *</label>
                  <input 
                    v-model="form.area" 
                    type="number" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                    required 
                    placeholder="Total area in square meters"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select 
                  v-model="form.status" 
                  class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="available">Available</option>
                  <option value="pending">Pending</option>
                  <option value="sold">Sold</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Property Images *</label>
                <input 
                  type="file" 
                  multiple 
                  @change="handleFileChange" 
                  class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100"
                  accept="image/*"
                  required
                />
                <p class="mt-1 text-sm text-gray-500">Upload multiple images of the property</p>
              </div>
            </div>

            <!-- Right Column - Location Details -->
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Property Location *</label>
                <div id="map" class="w-full h-64 border border-gray-300 rounded-md"></div>
                <p class="mt-1 text-sm text-gray-500">Click on the map to set property location</p>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Latitude *</label>
                  <input 
                    v-model="form.latitude" 
                    type="text" 
                    class="w-full border border-gray-300 rounded-md p-2 bg-gray-100" 
                    readonly 
                    required
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Longitude *</label>
                  <input 
                    v-model="form.longitude" 
                    type="text" 
                    class="w-full border border-gray-300 rounded-md p-2 bg-gray-100" 
                    readonly 
                    required
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Address *</label>
                <input 
                  v-model="form.address" 
                  class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                  required 
                  placeholder="Complete property address"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                  <input 
                    v-model="form.street" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Street name"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Purok/Barangay</label>
                  <input 
                    v-model="form.purok" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Purok or Barangay"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                  <input 
                    v-model="form.city" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                    required 
                    placeholder="City"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Province *</label>
                  <input 
                    v-model="form.province" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                    required 
                    placeholder="Province"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                  <input 
                    v-model="form.country" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                    required 
                    placeholder="Country"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                  <input 
                    v-model="form.postal_code" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Postal code"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="pt-4">
            <button 
              type="submit" 
              :disabled="form.processing"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
            >
              {{ form.processing ? 'Saving Property...' : 'Save Property' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>