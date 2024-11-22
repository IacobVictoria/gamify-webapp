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
        };
    },

    methods: {
        openConversation(friend) {
            this.selectedFriend = friend;
        },
     
    },
};
</script>
