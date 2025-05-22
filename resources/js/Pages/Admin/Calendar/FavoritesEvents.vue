<template>
    <div v-if="!selectedFavorite" class="favorites-container">
        <h3 class="text-lg font-semibold mb-4">⭐ Favorite {{ type }}</h3>

        <div v-if="favorites.length === 0">
            <p class="text-gray-500">Nu s-au găsit evenimente favorite.</p>
        </div>

        <ul v-else class="scrollable-list">
            <li v-for="fav in favorites" :key="fav.id" class="fav-item">
                <span @click="selectFavorite(fav)" class="cursor-pointer">{{
                    fav.title
                }}</span>
                <button class="btn-command" @click="editCommand(fav)">
                    ✏️ Editează
                </button>
            </li>
        </ul>
    </div>
    <FavoriteEventsDetails
        v-if="selectedFavorite"
        :command="selectedFavorite"
        :initialDate="selectedDate"
        :type="type"
        @close="selectedFavorite = null"
    />
</template>

<script setup>
import { ref } from "vue";
import FavoriteEventsDetails from "./FavoriteEventsDetails.vue";

const props = defineProps({
    favorites: Array,
    selectedDate: String,
    type: String,
});

const selectedFavorite = ref(null);

function selectFavorite(fav) {
    selectedFavorite.value = fav;
}

function editCommand(fav) {
    selectedFavorite.value = fav;
}
</script>

<style scoped>
.favorites-container {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 10px;
}

.scrollable-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
}

.fav-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

.cursor-pointer {
    cursor: pointer;
    color: #3498db;
}

.cursor-pointer:hover {
    text-decoration: underline;
}

.btn-command {
    background-color: #2ecc71;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-command:hover {
    background-color: #27ae60;
}

.favorite-details {
    margin-top: 20px;
    background-color: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-submit {
    background-color: #f39c12;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #e67e22;
}
</style>
