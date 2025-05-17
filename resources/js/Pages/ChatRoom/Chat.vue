<template>
    <AuthenticatedLayout>
        <div class="flex h-screen">
            <!-- Sidebar-ul cu lista de conversații -->
            <ChatSidebar
                :conversations="conversations"
                :current-user="currentUser"
                @selectConversation="openConversation"
            />

            <!-- Fereastra pentru conversația selectată -->
            <div class="flex-1">
                <ChatWindow
                    v-if="selectedFriend"
                    :friend="selectedFriend"
                    :currentUser="currentUser"
                />
                <div v-else class="flex items-center justify-center h-full">
                    <p>Selectează o conversație pentru a începe chatul</p>
                </div>
            </div>
            <div
                class="w-full md:w-80 max-h-[100vh] overflow-y-auto bg-white border border-gray-200 p-6"
            >
                <h3 class="text-xl font-semibold text-gray-800 mb-5">
                    👥 Adaugă Prieteni noi
                </h3>

                <!-- Search Input -->
                <input
                    v-model="searchQuery"
                    @input="searchUsers"
                    type="text"
                    placeholder="🔍 Caută după email..."
                    class="w-full p-3 border border-gray-300 rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />

                <!-- Results -->
                <ul v-if="searchResults.length > 0" class="space-y-3">
                    <li
                        v-for="user in searchResults"
                        :key="user.id"
                        class="flex justify-between items-center gap-3 bg-gray-50 border border-gray-100 rounded-lg px-3 py-3 hover:bg-blue-50 transition"
                    >
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <p class="font-semibold text-gray-800 truncate">
                                {{ user.name }}
                            </p>
                            <p class="text-gray-500 text-sm truncate">
                                {{ user.email }}
                            </p>
                        </div>

                        <button
                            @click="addFriend(user.id)"
                            class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg whitespace-nowrap"
                        >
                            Adaugă
                        </button>
                    </li>
                </ul>

                <!-- No Results -->
                <p v-else class="text-center text-sm text-gray-400 mt-6">
                    Niciun utilizator găsit. Încearcă un alt email.
                </p>
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
            if (this.selectedFriend && this.selectedFriend.id === friend.id) {
                return;
            }
            this.selectedFriend = null;
            this.$nextTick(() => {
                this.selectedFriend = friend;
            });
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
                await axios.post(`/user/user_friends/request`, {
                    receiver_id: userId,
                });
                this.searchResults = this.searchResults.filter(
                    (user) => user.id !== userId
                );
            } catch (error) {
                console.error("Error sending friend request:", error);
            }
        },
    },
};
</script>
