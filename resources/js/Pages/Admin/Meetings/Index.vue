<template>
    <AuthenticatedLayout>
        <div>
            <div class="text-center rounded-lg p-4 mb-6 ">
                <h2 class="text-2xl font-semibold mb-4 mt-8">📅 Meetings & Reports Calendar</h2>
                <p class="text-gray-700">
                    Select a date to schedule or manage your meetings. You can generate reports in
                    <strong>6 different categories</strong> to be sent directly in <strong>Discord</strong> at the start
                    date:
                </p>
                <ul class="mt-12 flex justify-center gap-3 flex-wrap">
                    <li class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded">💰 Sales Stock Monthly</li>
                    <li class="bg-blue-200 text-blue-800 px-3 py-1 rounded">👤 User Activity Monthly</li>
                    <li class="bg-green-200 text-green-800 px-3 py-1 rounded">📊 Sales Stock Monthly</li>
                    <li class="bg-red-200 text-red-800 px-3 py-1 rounded">📢 Rewards Activity Monthly</li>
                    <li class="bg-purple-200 text-purple-800 px-3 py-1 rounded">🖥️ NPS Report</li>
                    <li class="bg-teal-200 text-teal-800 px-3 py-1 rounded">🎮 Games Activity Monthly</li>
                </ul>
            </div>
            <ScheduleXCalendar :calendar-app="calendarApp">
                <template #eventModal="{ calendarEvent }">
                    <div :style="eventModalStyles">
                        <MeetingComponent :calendarMeeting="calendarEvent" :categories="categories" :periods="periods"
                            :selectedDate="selectedDate">
                        </MeetingComponent>
                    </div>
                </template>
            </ScheduleXCalendar>

            <AddMeetingForm v-if="showModal" :selectedDate="selectedDate" :showModal="showModal"
                :categories="categories" @update:showModal="showModal = $event" @submit="handleSubmit"
                @closeForm="closeModal" :periods="periods" :isPastDate="isPastDate" />

            <ReportsExplanation></ReportsExplanation>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { ScheduleXCalendar } from '@schedule-x/vue'
import { createCalendar, createViewDay, createViewWeek, createViewMonthGrid } from '@schedule-x/calendar'
import '@schedule-x/theme-default/dist/index.css'
import { createEventsServicePlugin } from '@schedule-x/events-service'
import { createEventModalPlugin } from '@schedule-x/event-modal'
import { createCalendarControlsPlugin } from '@schedule-x/calendar-controls'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import MeetingComponent from './MeetingComponent.vue'
import AddMeetingForm from './AddMeetingForm.vue'
import ReportsExplanation from './ReportsExplanation.vue'

const eventsServicePlugin = createEventsServicePlugin();
const eventModal = createEventModalPlugin();

const calendarControls = createCalendarControlsPlugin()

const props = defineProps({
    meetings: {
        type: Array,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
    periods: {
        type: Array,
        required: true
    },

})

const calendarApp = createCalendar({
    views: [
        createViewDay(),
        createViewWeek(),
        createViewMonthGrid(),
    ],
    events: props.meetings,
    calendars: {
        personal: {
            colorName: 'personal',
            lightColors: {
                main: '#f9d71c',
                container: '#fff5aa',
                onContainer: '#594800',
            },
            darkColors: {
                main: '#fff5c0',
                onContainer: '#fff5de',
                container: '#a29742',
            },
        },
        work: {
            colorName: 'work',
            lightColors: {
                main: '#f91c45',
                container: '#ffd2dc',
                onContainer: '#59000d',
            },
            darkColors: {
                main: '#ffc0cc',
                onContainer: '#ffdee6',
                container: '#a24258',
            },
        },
        leisure: {
            colorName: 'leisure',
            lightColors: {
                main: '#1cf9b0',
                container: '#dafff0',
                onContainer: '#004d3d',
            },
            darkColors: {
                main: '#c0fff5',
                onContainer: '#e6fff5',
                container: '#42a297',
            },
        },
        school: {
            colorName: 'school',
            lightColors: {
                main: '#1c7df9',
                container: '#d2e7ff',
                onContainer: '#002859',
            },
            darkColors: {
                main: '#c0dfff',
                onContainer: '#dee6ff',
                container: '#426aa2',
            },
        },
    },
    plugins: [
        eventModal,
        eventsServicePlugin,
        calendarControls,
    ],
    callbacks: {
        onClickDate(date) {
            selectedDate.value = date;
            showModal.value = true;
        },
    },
});
calendarControls.setView('month-grid');
onMounted(() => {
    console.log(props.events)
});
const selectedDate = ref(null)
const showModal = ref(false)


const eventModalStyles = {
    boxShadow: '0 0 2em #123'
}
function closeModal() {
    showModal.value = false
}

function handleSubmit(formData) {
    closeModal()
}

const today = computed(() => {
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    return now;
});

const isPastDate = computed(() => {
    if (!selectedDate.value) return false;
    const selected = new Date(selectedDate.value);
    selected.setHours(0, 0, 0, 0);
    return selected < today.value;
});
</script>

<style scoped>
.sx-vue-calendar-wrapper {
    height: 500px;
    width: 1000px;
    max-height: 90vh;
    margin-bottom: 7em;
    margin-left: 10em;
    margin-right: 10em;
}
</style>