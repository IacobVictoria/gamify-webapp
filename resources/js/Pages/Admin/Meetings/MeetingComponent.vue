<template>
    <div
        v-if="!showModal && !eventDeleted"
        class="max-w-xl mx-auto bg-white rounded-2xl shadow-2xl p-6"
    >
        <div
            class="flex items-center justify-center gap-3 mb-6 bg-gray-50 px-6 py-4 rounded-xl shadow border border-gray-200"
        >
            <h2 class="text-lg text-gray-700 font-semibold">
                📅 Întâlnire Programată
            </h2>
        </div>

        <div class="order-header flex items-center gap-2 mb-2">
            <span class="text-xl">🏷️</span>
            <h3 class="text-xl font-semibold">{{ calendarMeeting.title }}</h3>
        </div>

        <div class="flex items-start gap-2 mb-2">
            <span class="text-xl">📝</span>
            <p class="text-gray-700">{{ calendarMeeting.description }}</p>
        </div>
        <div class="text-center mb-4">
            <span class="font-medium mr-4">Status:</span>
            <span
                class="font-semibold"
                :class="
                    calendarMeeting.status === 'CLOSED'
                        ? 'text-red-500'
                        : 'text-green-500'
                "
            >
                {{ calendarMeeting.status }}
            </span>
        </div>

        <div class="flex justify-center items-center gap-2 mb-4">
            <span role="img" aria-label="clock">⏰</span>
            <span><strong>Data:</strong> {{ calendarMeeting.start }}</span>
            <span>|</span>
            <span><strong>Perioada:</strong> {{ calendarMeeting.period }}</span>
        </div>

        <div class="categories mb-4">
            <h4 class="text-center font-semibold mb-2">Categorii</h4>
            <ul class="flex justify-center gap-2 flex-wrap">
                <li
                    v-for="category in formattedCategories"
                    :key="category.name"
                    :style="{ backgroundColor: category.color }"
                    class="px-3 py-1 rounded text-white flex gap-1 items-center"
                >
                    <span>{{ category.icon }}</span>
                    <span>{{ category.name }}</span>
                </li>
            </ul>
        </div>

        <div class="flex justify-center gap-4">
            <button
                v-if="
                    calendarMeeting.status !== 'CLOSED' &&
                    this.$page.props.user.id === calendarMeeting.admin_id
                "
                @click="editEvent"
                class="bg-blue-500 px-4 py-2 rounded"
            >
                ✏️ Editează
            </button>

            <button
                v-if="
                    calendarMeeting.status !== 'CLOSED' &&
                    this.$page.props.user.id === calendarMeeting.admin_id
                "
                @click="deleteEvent"
                class="bg-red-500 px-4 py-2 rounded"
            >
                ❌ Șterge
            </button>

            <button
                v-if="calendarMeeting.status === 'CLOSED'"
                @click="viewReports"
                class="bg-green-500 px-4 py-2 rounded"
            >
                📄 Rapoarte
            </button>
        </div>
    </div>

    <EditMeetingForm
        :show-modal="showModal"
        :calendar-event="calendarMeeting"
        :categories="categories"
        :periods="periods"
        @close="closeModal"
        :selected-date="selectedDate"
        :edit-route="'admin.meetings.update'"
    />
</template>
<script>
import EditMeetingForm from "./EditMeetingForm.vue";

export default {
    props: {
        calendarMeeting: Object,
        showModal: Boolean,
        periods: Array,
        categories: Array,
        selectedDate: Date,
    },
    data() {
        return {
            showModal: this.showModal,
            eventDeleted: false,
            categoryStyles: {
                sales_stock: { icon: "💰", color: "#f39c12" },
                user_activity: { icon: "👤", color: "#3498db" },
                sales_stock: { icon: "📊", color: "#2ecc71" },
                nps_report: { icon: "🖥️", color: "#9b59b6" },
            },
        };
    },
    computed: {
        formattedCategories() {
            return this.calendarMeeting.categories.map((category) => {
                return {
                    name: category,
                    icon: this.categoryStyles[category]?.icon || "📌",
                    color: this.categoryStyles[category]?.color || "#95a5a6",
                };
            });
        },
    },
    components: {
        EditMeetingForm,
    },
    methods: {
        editEvent() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        deleteEvent() {
            this.$inertia.delete(
                route("admin.meetings.destroy", {
                    id: this.calendarMeeting.id,
                }),
                {
                    onSuccess: () => {
                        this.eventDeleted = true;
                        window.location.reload();
                        this.closeModal();
                    },
                    onError: (error) => {
                        console.error("Error deleting event:", error);
                    },
                }
            );
        },
        viewReports() {
            this.$emit("showReports", this.calendarMeeting.reports);
        },
    },
};
</script>
<style scoped>
.categories {
    margin-top: 10px;
}

.categories ul {
    list-style: none;
    padding: 0;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.categories li {
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
    color: white;
    display: flex;
    align-items: center;
    gap: 5px;
}
</style>
