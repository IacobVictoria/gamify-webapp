<template>
    <div class="w-full md:w-1/3 bg-white shadow-md rounded-lg p-5 h-full z-50 overflow-y-auto">
      <input
        type="text"
        v-model="searchQuery"
        @input="searchFriendConversation"
        placeholder="🔍 Caută prieten..."
        class="w-full p-3 border rounded-lg mb-5 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
  
      <h2 class="text-xl font-bold text-gray-800 mb-4">💬 Conversații</h2>
  
      <ul v-if="searchResults.length > 0" class="space-y-3">
        <li
          v-for="conversation in searchResults"
          :key="conversation.friend.id"
          @click="selectConversation(conversation.friend)"
          class="flex items-center justify-between p-3 rounded-lg cursor-pointer bg-gray-100 hover:bg-blue-100 transition"
        >
          <!-- avatar + nume -->
          <div class="flex items-center gap-3">
            <img  class="w-8 h-8 rounded-full"
            :src="conversation.friend.gender === 'Male' ? '/images/male.png' : '/images/female.png'" alt="User Avatar" />
            <div>
              <p class="font-semibold text-gray-800">{{ conversation.friend.name }}</p>
              <p
                class="text-sm"
                :class="conversation.status === 'Online' ? 'text-green-600' : 'text-gray-500'"
              >
                {{ conversation.status }}
              </p>
            </div>
          </div>
  
          <!--badge pentru mesaje necitite -->
          <div v-if="conversation.unreadCount > 0"
            class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
            {{ conversation.unreadCount }}
          </div>
        </li>
      </ul>
     <p v-else class="text-sm text-gray-500 text-center mt-10">Nicio conversație găsită.</p>
    </div>
  </template>

<script>
export default {
    props: {
        conversations: {
            type: Array,
            required: true,
        },
        currentUser: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            selectedConversation: null,
            searchQuery: '',
            searchResults: [],

        };
    },
    methods: {
        async searchFriendConversation() {
            try {
                const response = await axios.get('/user/user_chat/searchFriendConversation',
                    {
                        params: { emailFriend: this.searchQuery }
                    });
                this.searchResults = response.data;
            } catch (error) {
                console.error("Error searching friends:", error);
                this.searchResults = [];
            }

        },
        selectConversation(friend) {
            this.selectedConversation = friend.id;

            // mark the conversation as opened
            const conversation = this.conversations.find(convo => convo.friend.id === friend.id);
            if (conversation) {
                if (!conversation.hasBeenOpened) {
                    // it's the first time opening, reset unread count and mark as opened
                    conversation.unreadCount = 0;
                    conversation.hasBeenOpened = true;
                }
            }

            this.$emit('selectConversation', friend);
        },
    },
    mounted() {
        this.searchResults = this.conversations;

        window.Echo.private(`user_message.${this.currentUser.id}`)
            .listen('.MessageUnreadUpdated', (event) => {
                const conversation = this.conversations.find(convo => convo.friend.id === event.friendId);

                if (conversation) {
                    if (this.selectedConversation !== event.friendId && !conversation.hasBeenOpened) {
                        // if conversation is not opened and not yet marked, update unread count
                        conversation.unreadCount = event.unreadCount;
                    }
                }
            });

        window.Echo.channel('chat_status')
            .listen('.UserStatusChanged', (event) => {
                const conversation = this.conversations.find(convo => convo.friend.id === event.userId);
                if (conversation) {
                    conversation.status = event.status;
                }
            });

    },
};
</script>

<style scoped>
.notification-icon {
    position: relative;
    cursor: pointer;
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    margin: 10px;
    background-color: red;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    text-align: center;
    font-size: 12px;
    line-height: 20px;
}
</style>
