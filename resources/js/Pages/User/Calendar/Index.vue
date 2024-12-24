<template>
    <AuthenticatedLayout>
        <div>
            <ScheduleXCalendar :calendar-app="calendarApp">
                <template #eventModal="{ calendarEvent }">
                    <div :style="eventModalStyles">
                        <div v-if="calendarEvent.type === 'event'">
                            <CustomEventComponent :calendar-event="calendarEvent" :editMode="false" :-is-user="true" />
                        </div>
                        <div v-if="calendarEvent.type === 'discount'">
                            <DiscountEventComponent :calendar-event="calendarEvent" :categories="props.categories" :editMode="false" />
                        </div>
                    </div>
                </template>
            </ScheduleXCalendar>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ScheduleXCalendar } from '@schedule-x/vue'
import { createCalendar, createViewDay, createViewWeek, createViewMonthGrid } from '@schedule-x/calendar'
import '@schedule-x/theme-default/dist/index.css'
import { createEventsServicePlugin } from '@schedule-x/events-service'
import { createEventModalPlugin } from '@schedule-x/event-modal'
import { createCalendarControlsPlugin } from '@schedule-x/calendar-controls'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CustomEventComponent from '@/Pages/Admin/Calendar/CustomEventComponent.vue'
import DiscountEventComponent from '@/Pages/Admin/Calendar/DiscountEventComponent.vue'

const eventsServicePlugin = createEventsServicePlugin();
const eventModal = createEventModalPlugin();

const calendarControls = createCalendarControlsPlugin()

const props = defineProps({
    events: {
        type: Array,
        required: true
    }, categories: Array,
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

</script>

<style scoped>
.sx-vue-calendar-wrapper {
    height: 700px;
    max-height: 90vh;
    margin-bottom: 7em;
    margin-left: 10em;
    margin-right: 10em;
    margin-top: 4em;
}
</style>