<template>
    <AuthenticatedLayout>
        <div class="flex h-screen">
            <!-- Sidebar-ul cu lista de conversații -->
            <ChatSidebar :conversations="conversations" :current-user="currentUser"
                @selectConversation="openConversation" />

            <!-- Fereastra pentru conversația selectată -->
            <div class="flex-1">
                <ChatWindow v-if="selectedFriend" :friend="selectedFriend" :currentUser="currentUser" />
                <div v-else class="flex items-center justify-center h-full">
                    <p>Selectează o conversație pentru a începe chatul</p>
                </div>
            </div>
            <div class="w-80 border-l bg-gray-100 p-4">
                <h3 class="text-lg font-bold mb-4">Add Friends</h3>
                <input v-model="searchQuery" @input="searchUsers" type="text" placeholder="Search by email..."
                    class="w-full p-2 border rounded mb-4" />
                <ul>
                    <li v-for="user in searchResults" :key="user.id" class="flex justify-between items-center mb-2">
                        <span>{{ user.name }} ({{ user.email }})</span>
                        <button @click="addFriend(user.id)"
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            Add Friend
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ChatSidebar from "./ChatSidebar.vue";
import ChatWindow from "./ChatWindow.vue";

export default {
    components: {
        AuthenticatedLayout,
        ChatSidebar,
        ChatWindow,
    },
    props: {
        currentUser: {
            type: Object,
            required: true,
        },
        conversations: {
            type: Array,
            required: true,
        },
        
    },
    data() {
        return {
            selectedFriend: null,
            searchQuery: "",
            searchResults: [],
        };
    },

    methods: {
        openConversation(friend) {
            this.selectedFriend = friend;
        },
        async searchUsers() {
            if (this.searchQuery.trim() === "") {
                this.searchResults = [];
                return;
            }

            try {
                const response = await axios.get(`/user/user_friends/search`, {
                    params: { email: this.searchQuery },
                });
                this.searchResults = response.data; 
            } catch (error) {
                console.error("Error searching users:", error);
            }
        },
        async addFriend(userId) {
            try {
                await axios.post(`/user/user_friends/request`, { receiver_id: userId });
                this.searchResults = this.searchResults.filter((user) => user.id !== userId);
             
            } catch (error) {
                console.error("Error sending friend request:", error);
            }
        },

    },
};
</script>
