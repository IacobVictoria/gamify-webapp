<template>
    <div>
        <ScheduleXCalendar :calendar-app="calendarApp">
            <template #eventModal="{ calendarEvent }">
                <div :style="eventModalStyles">
                    <div v-if="calendarEvent.type === 'event'">
                        <CustomEventComponent :calendar-event="calendarEvent" />
                    </div>
                    <div v-if="calendarEvent.type === 'discount'">
                        <DiscountEventComponent :calendar-event="calendarEvent" :categories="props.categories"/>
                    </div>
                    <div v-if="calendarEvent.type === 'supplier_order'">
                        <SupplierOrderComponent :calendar-event="calendarEvent" :products="props.products" :suppliers="props.suppliers"/>
                    </div>
                </div>

            </template>
        </ScheduleXCalendar>

        <AddEventModal v-if="showModal" :selectedDate="selectedDate" :showModal="showModal" :categories="props.categories"
            @update:showModal="showModal = $event" @submit="handleSubmit" :suppliers="props.suppliers" :products="props.products"/>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { ScheduleXCalendar } from '@schedule-x/vue'
import { createCalendar, createViewDay, createViewWeek, createViewMonthGrid } from '@schedule-x/calendar'
import '@schedule-x/theme-default/dist/index.css'
import AddEventModal from './AddEventModal.vue'
import { createEventsServicePlugin } from '@schedule-x/events-service'
import { createEventModalPlugin } from '@schedule-x/event-modal'
import { createCalendarControlsPlugin } from '@schedule-x/calendar-controls'
import CustomEventComponent from './CustomEventComponent.vue'
import DiscountEventComponent from './DiscountEventComponent.vue'
import SupplierOrderComponent from './SupplierOrderComponent.vue'

const eventsServicePlugin = createEventsServicePlugin();
const eventModal = createEventModalPlugin();

const calendarControls = createCalendarControlsPlugin()
const props = defineProps({
    events: {
        type: Array,
        required: true
    }, categories: Array,
    products: Array,
    suppliers: Array
})

const calendarApp = createCalendar({
    views: [
        createViewDay(),
        createViewWeek(),
        createViewMonthGrid(),
    ],
    events: props.events,
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

const selectedDate = ref(null)
const showModal = ref(false)


const eventModalStyles = {
    boxShadow: '0 0 2em #123'
}
function closeModal() {
    showModal.value = false
}

function handleSubmit(formData) {
    console.log('Form data submitted:', formData)
    closeModal()
}
</script>

<style scoped>
.sx-vue-calendar-wrapper {
    height: 700px;
    max-height: 90vh;
}
</style>