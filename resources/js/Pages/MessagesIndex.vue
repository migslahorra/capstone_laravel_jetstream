<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
  messages: Array,
  property_id: [Number, String],
  presetMessage: String,
  propertyOwnerId: [Number, null],
});

const propertyId = Number(props.property_id);
const newMessage = ref(props.presetMessage || '');
const page = usePage();
const authUser = page.props.auth.user;

const otherUserId = computed(() => {
  if (props.messages.length > 0) {
    const first = props.messages[0];
    return first.sender_id === authUser.id ? first.receiver_id : first.sender_id;
  }

  const ownerId = props.propertyOwnerId;
  if (ownerId && ownerId !== authUser.id) {
    return ownerId;
  }

  return null;
});

function sendMessage() {
  if (!newMessage.value.trim()) return;

  if (!otherUserId.value) {
    alert('Cannot determine the recipient.');
    return;
  }

  router.post('/messages', {
    receiver_id: otherUserId.value,
    property_id: propertyId,
    content: newMessage.value,
  }, {
    onSuccess: () => {
      newMessage.value = '';
    }
  });
}
</script>

<template>
  <AppLayout title="Conversation">
    <template #header>
      <div class="flex items-center gap-3 py-2">
        <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"/><circle cx="12" cy="12" r="3"/></svg>
        <h2 class="font-extrabold text-2xl text-green-700 tracking-tight">Conversation</h2>
      </div>
    </template>

    <div class="p-0 md:p-6 flex flex-col h-[70vh] md:h-[65vh] bg-gradient-to-br from-green-100 via-white to-blue-100 rounded-xl shadow max-w-2xl mx-auto">
      <div class="flex-1 overflow-y-auto px-2 md:px-6 py-4 flex flex-col gap-2 w-full">
        <div v-if="messages.length">
          <div
            v-for="msg in messages"
            :key="msg.id"
            :class="[
              'flex',
              msg.sender_id === authUser.id ? 'justify-end' : 'justify-start'
            ]"
          >
            <div
              :class="[
                'max-w-[70%] md:max-w-[60%] p-3 rounded-2xl shadow',
                msg.sender_id === authUser.id
                  ? 'bg-green-600 text-white rounded-br-none'
                  : 'bg-white text-gray-800 border border-green-200 rounded-bl-none'
              ]"
            >
              <div class="text-xs mb-1" :class="msg.sender_id === authUser.id ? 'text-green-100' : 'text-green-700'">
                {{ msg.sender_id === authUser.id ? 'You' : (msg.sender?.email || 'User') }}
              </div>
              <div class="break-words">{{ msg.message }}</div>
            </div>
          </div>
        </div>
        <div v-else class="flex flex-col items-center justify-center h-full text-gray-500">
          <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"/><circle cx="12" cy="12" r="3"/></svg>
          <p>This is the start of your conversation.</p>
        </div>
      </div>

      <div class="p-4 bg-white border-t rounded-b-xl flex flex-col md:flex-row gap-2 items-end md:items-center">
        <textarea
          v-model="newMessage"
          placeholder="Type your message..."
          class="w-full border border-green-200 p-3 rounded-2xl focus:ring-2 focus:ring-green-400 focus:outline-none resize-none min-h-[44px]"
          rows="2"
        ></textarea>
        <button
          @click="sendMessage"
          class="mt-2 md:mt-0 md:ml-2 px-6 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition"
        >
          Send
        </button>
      </div>

      <div class="mt-2 px-4 pb-2">
        <a href="/messages" class="text-green-700 font-semibold hover:underline">← Back to Inbox</a>
      </div>
    </div>
  </AppLayout>
</template>
