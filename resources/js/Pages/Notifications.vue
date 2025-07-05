
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
    <template #header>
      <div class="flex items-center justify-between py-4 px-2 bg-gradient-to-r from-green-600 to-green-400 rounded-t-lg shadow">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-bell text-white text-2xl"></i>
          <h2 class="font-bold text-2xl text-white tracking-tight">Notifications</h2>
        </div>
      </div>
    </template>

    <div class="py-10 px-2 bg-white min-h-screen">
      <div class="max-w-3xl mx-auto">
        <div v-if="!notifications.length" class="text-center py-20">
          <div class="inline-block bg-white px-8 py-6 rounded-lg shadow border border-green-200">
            <i class="fa-regular fa-bell-slash text-4xl text-gray-400 mb-4"></i>
            <p class="text-lg text-gray-700 font-semibold">No notifications yet.</p>
            <p class="text-gray-500">You have no notifications at this time.</p>
          </div>
        </div>
        <div v-else class="space-y-4">
          <div
            v-for="notification in notifications"
            :key="notification.id"
            class="flex items-start gap-4 bg-white border border-green-100 rounded-xl shadow p-5 hover:shadow-lg transition relative"
            :class="notification.is_read ? 'opacity-60' : ''"
          >
            <div class="flex-shrink-0">
              <i class="fa-solid fa-circle-info text-green-600 text-2xl"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <span class="font-semibold text-green-900">{{ notification.type }}</span>
                <span v-if="!notification.is_read" class="ml-2 px-2 py-0.5 text-xs bg-green-600 text-white rounded-full">New</span>
              </div>
              <div class="text-gray-800 mb-1">{{ notification.message }}</div>
              <div class="text-xs text-gray-500 flex gap-2">
                <span>Received: {{ new Date(notification.created_at).toLocaleString() }}</span>
                <span v-if="notification.is_read" class="ml-2 text-green-600">Read</span>
              </div>
            </div>
            <div class="flex flex-col gap-2 items-end">
              <button
                v-if="!notification.is_read"
                :disabled="loading"
                @click="markAsRead(notification.id)"
                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded shadow text-xs font-semibold transition"
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
