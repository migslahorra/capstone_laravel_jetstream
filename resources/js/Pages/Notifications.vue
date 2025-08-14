<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const page = usePage();
const notifications = ref(page.props.notifications || []);
const loading = ref(false);

function markAsRead(notificationId) {
  loading.value = true;
  router.post(`/notifications/${notificationId}/read`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      const notif = notifications.value.find(n => n.id === notificationId);
      if (notif) notif.is_read = 1;
      loading.value = false;
    },
    onError: () => {
      loading.value = false;
    }
  });
}
</script>

<template>
  <AppLayout title="Notifications">
    <!-- Header -->
    <template #header>
      <div class="flex items-center justify-between py-5 px-4 bg-gradient-to-r from-green-50 via-white to-blue-50 rounded-t-2xl shadow-sm border-b border-gray-200">
        <div class="flex items-center gap-3">
          <div class="bg-green-600 p-2 rounded-full shadow">
            <i class="fa-solid fa-bell text-white text-xl"></i>
          </div>
          <h2 class="font-extrabold text-2xl text-green-700 tracking-tight">Notifications</h2>
        </div>
      </div>
    </template>

    <!-- Main Content -->
    <div class="py-10 px-4 bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-screen">
      <div class="max-w-4xl mx-auto">
        
        <!-- Empty State -->
        <div v-if="!notifications.length" class="text-center py-20">
          <div class="inline-block bg-white px-8 py-6 rounded-2xl shadow-lg border border-green-200">
            <i class="fa-regular fa-bell-slash text-4xl text-gray-400 mb-4"></i>
            <p class="text-lg text-green-700 font-semibold">No notifications yet.</p>
            <p class="text-gray-500">You have no notifications at this time.</p>
          </div>
        </div>

        <!-- Notifications List -->
        <div v-else class="space-y-6">
          <div
            v-for="notification in notifications"
            :key="notification.id"
            class="bg-white border border-green-100 rounded-2xl shadow-lg hover:shadow-xl transition p-6 flex gap-4 items-start"
            :class="notification.is_read ? 'opacity-60' : ''"
          >
            <!-- Icon -->
            <div class="flex-shrink-0">
              <i class="fa-solid fa-circle-info text-green-600 text-2xl"></i>
            </div>

            <!-- Content -->
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <span class="font-semibold text-green-900">{{ notification.type }}</span>
                <span v-if="!notification.is_read" class="ml-2 px-2 py-0.5 text-xs bg-green-600 text-white rounded-full">New</span>
              </div>
              <div class="text-gray-700 mb-1">{{ notification.message }}</div>
              <div class="text-xs text-gray-500 flex gap-2">
                <span>Received: {{ new Date(notification.created_at).toLocaleString() }}</span>
                <span v-if="notification.is_read" class="ml-2 text-green-600">Read</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-2 items-end">
              <button
                v-if="!notification.is_read"
                :disabled="loading"
                @click="markAsRead(notification.id)"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow text-xs font-semibold transition-colors duration-200"
              >
                Mark as Read
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template>
