<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;

function isBuyer() {
  return user && (user.role === 'Buyer' || user.role === 'Buyer and Seller');
}
function isSeller() {
  return user && (user.role === 'Seller' || user.role === 'Buyer and Seller');
}
</script>

<template>
  <AppLayout title="LandSeek Dashboard">
    <template #header>
      <div class="flex items-center justify-between py-4 px-2 bg-gradient-to-r from-green-100 via-white to-blue-100 rounded-t-lg shadow-sm">
        <div class="flex items-center gap-3">
          <img src="/resources/image/landseek_logo.jpg" alt="LandSeek Logo" class="w-10 h-10 rounded-full border-2 border-green-400 bg-white shadow" />
          <div>
            <h2 class="font-extrabold text-2xl text-green-700 tracking-tight">LandSeek Dashboard</h2>
            <p class="text-sm text-gray-500">Your digital marketplace for land hunting</p>
          </div>
        </div>
        <div class="hidden md:flex gap-4">
          <Link :href="route('properties')" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition">My Properties</Link>
          <Link :href="route('properties')" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition">Find Land</Link>
          <Link :href="route('messages')" class="inline-block px-4 py-2 bg-yellow-500 text-white rounded-lg font-semibold shadow hover:bg-yellow-600 transition">Messages</Link>
        </div>
      </div>
    </template>

    <div class="py-10 px-2 bg-gradient-to-br from-green-100 via-white to-blue-100 min-h-[80vh]">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Welcome Card -->
        <div class="col-span-2 bg-white rounded-2xl shadow-lg p-8 flex flex-col justify-center">
          <h3 class="text-2xl font-bold text-green-700 mb-2">Welcome to LandSeek!</h3>
          <p class="text-gray-700 mb-4">Manage your land listings, connect with agents, and explore new opportunities. Use the quick links below to get started.</p>
          <div class="flex flex-wrap gap-4 mt-4">
            <template v-if="isSeller()">
              <Link :href="route('properties.store')" class="px-5 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition">Add New Property</Link>
            </template>
            <template v-if="isBuyer()">
              <Link :href="route('properties')" class="px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition">Browse Listings</Link>
            </template>
            <Link :href="route('messages')" class="px-5 py-2 bg-yellow-500 text-white rounded-lg font-semibold shadow hover:bg-yellow-600 transition">View Messages</Link>
          </div>
        </div>
        <!-- User Stats Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center">
          <h4 class="text-lg font-bold text-green-700 mb-4">Your Activity</h4>
          <div class="w-full flex flex-col gap-3">
            <div class="flex items-center justify-between">
              <span class="text-gray-600">Listed Properties</span>
              <span class="font-bold text-green-700">3</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-600">Active Inquiries</span>
              <span class="font-bold text-blue-700">5</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-600">Agent Chats</span>
              <span class="font-bold text-yellow-600">2</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
