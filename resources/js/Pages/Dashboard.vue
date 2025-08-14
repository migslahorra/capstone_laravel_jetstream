<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
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
    <!-- Header -->
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-5 px-4 bg-gradient-to-r from-green-50 via-white to-blue-50 rounded-t-2xl shadow-sm border-b border-gray-200">
        <!-- Logo + Title -->
        <div class="flex items-center gap-4">
          <img
            src="/images/landseek_logo.png"
            alt="LandSeek Logo"
            class="w-14 h-14 rounded-full border-2 border-green-500 bg-white shadow"
          />
          <div>
            <h2 class="text-2xl font-extrabold text-green-700 leading-tight">
              LandSeek Dashboard
            </h2>
            <p class="text-sm text-gray-500">Your digital marketplace for land hunting</p>
          </div>
        </div>

        <!-- Nav Links -->
        <div class="flex flex-wrap gap-3">
          <Link
            v-if="isSeller()"
            :href="route('properties')"
            class="px-5 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition-colors duration-200"
          >
            My Properties
          </Link>
          <Link
            :href="route('properties')"
            class="px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition-colors duration-200"
          >
            Find Land
          </Link>
          <Link
            :href="route('messages')"
            class="px-5 py-2 bg-yellow-500 text-white rounded-lg font-semibold shadow hover:bg-yellow-600 transition-colors duration-200"
          >
            Messages
          </Link>
        </div>
      </div>
    </template>

    <!-- Body -->
    <div class="py-10 px-4 bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-[80vh]">
      <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Welcome Card -->
        <div class="col-span-2 bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-200">
          <h3 class="text-2xl font-bold text-green-700 mb-2">Welcome to LandSeek!</h3>
          <p class="text-gray-600 mb-6">
            Manage your land listings, connect with agents, and explore new opportunities.
            Use the quick links below to get started.
          </p>
          <div class="flex flex-wrap gap-4">
            <template v-if="isSeller()">
              <Link
                :href="route('properties.store')"
                class="px-5 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition-colors duration-200"
              >
                Add New Property
              </Link>
            </template>
            <template v-if="isBuyer()">
              <Link
                :href="route('properties')"
                class="px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition-colors duration-200"
              >
                Browse Listings
              </Link>
            </template>
            <Link
              :href="route('messages')"
              class="px-5 py-2 bg-yellow-500 text-white rounded-lg font-semibold shadow hover:bg-yellow-600 transition-colors duration-200"
            >
              View Messages
            </Link>
          </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-200">
          <h4 class="text-lg font-bold text-green-700 mb-6">Your Activity</h4>
          <div class="space-y-4">
            <div class="flex items-center justify-between border-b pb-2">
              <span class="text-gray-600">Listed Properties</span>
              <span class="font-bold text-green-700">3</span>
            </div>
            <div class="flex items-center justify-between border-b pb-2">
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
