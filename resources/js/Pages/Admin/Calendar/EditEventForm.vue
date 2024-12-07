<template>
    <div class="modal-overlay">
        <div class="modal-content">
            <h3 class="text-lg font-semibold text-gray-900">Edit Event</h3>

            <form @submit.prevent="submitForm">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                    <input v-model="formData.title" type="text" id="title" placeholder="Enter event title"
                        class="input" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="formData.description" id="description" placeholder="Enter event description"
                        class="input"></textarea>
                </div>

                <div>
                    <label for="startTime" class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input v-model="formData.start" type="datetime-local" id="startTime" class="input"
                        :min="startTimeMin" />
                </div>

                <div>
                    <label for="endTime" class="block text-sm font-medium text-gray-700">End Time</label>
                    <input v-model="formData.end" type="datetime-local" id="endTime" class="input"
                        :min="formData.start" />
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select v-model="formData.status" id="status" class="input">
                        <option value="OPEN">OPEN</option>
                        <option value="CLOSED">CLOSED</option>
                    </select>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        calendarEvent: Object
    },
    data() {
        return {
            formData: {
                title: this.calendarEvent.title || '',
                description: this.calendarEvent.description || '',
                start: this.toDateTimeLocalFormat(this.calendarEvent.start) || '',
                end: this.toDateTimeLocalFormat(this.calendarEvent.end) || '',
                status: this.calendarEvent.status || 'OPEN',
            }
        };
    },
    computed: {
        startTimeMin() {
            return `${new Date().toISOString().split("T")[0]}T00:00`;
        }
    },
    methods: {
        toDateTimeLocalFormat(datetime) {
            if (!datetime) return '';
            return datetime.replace(' ', 'T'); // Înlocuim spațiul cu 'T' pentru compatibilitate
        },
        formatDate(dateTime) {
            if (!dateTime) return null;

            const date = new Date(dateTime);

            if (dateTime.includes("T")) {
                return date.toISOString().slice(0, 16).replace('T', ' ');
            } else {
                return date.toISOString().slice(0, 10);
            }
        },
        submitForm() {
            if (new Date(this.formData.end) <= new Date(this.formData.start)) {
                alert("End time must be greater than start time.");
                return;
            }
            this.formData.start = this.formatDate(this.formData.start)
            this.formData.end = this.formatDate(this.formData.end)
            this.$inertia.put(route('admin.calendar.event.update', { id: this.calendarEvent.id }), {
                payload: this.formData
            });
            this.$emit('formSubmitted');
        }
    }
};
</script>

<style scoped></style>
