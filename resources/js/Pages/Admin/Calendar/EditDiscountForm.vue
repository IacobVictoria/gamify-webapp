<template>
    <div class="modal-overlay">
        <div class="modal-content">
            <h3 class="text-lg font-semibold text-gray-900">
                Editează Reducerea
            </h3>

            <form @submit.prevent="submitForm">
                <!-- Titlu Discount -->
                <div>
                    <label
                        for="title"
                        class="block text-sm font-medium text-gray-700"
                        >Titlul reducerii</label
                    >
                    <input
                        v-model="formData.title"
                        type="text"
                        id="title"
                        placeholder="Introdu titlul reducerii"
                        class="input"
                        required
                    />
                </div>

                <!-- Descriere Discount -->
                <div>
                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700"
                        >Descriere</label
                    >
                    <textarea
                        v-model="formData.description"
                        id="description"
                        placeholder="Introdu descrierea reducerii"
                        class="input"
                    ></textarea>
                </div>

                <!-- Data Start -->
                <div>
                    <label
                        for="startTime"
                        class="block text-sm font-medium text-gray-700"
                        >Dată început</label
                    >
                    <input
                        v-model="formData.start"
                        type="datetime-local"
                        id="startTime"
                        class="input"
                        :min="startTimeMin"
                    />
                </div>

                <!-- Data End -->
                <div>
                    <label
                        for="endTime"
                        class="block text-sm font-medium text-gray-700"
                        >Dată sfârșit</label
                    >
                    <input
                        v-model="formData.end"
                        type="datetime-local"
                        id="endTime"
                        class="input"
                        :min="formData.start"
                    />
                </div>

                <!-- Aplică la -->
                <div class="mt-2">
                    <label
                        for="applyTo"
                        class="block text-sm font-medium text-gray-700"
                        >Se aplică la</label
                    >
                    <select
                        v-model="formData.applyTo"
                        id="applyTo"
                        class="input"
                    >
                        <option value="all">Toate produsele</option>
                        <option value="categories">Categorie specifică</option>
                    </select>
                </div>

                <div v-if="formData.applyTo === 'categories'" class="mt-2">
                    <label
                        for="category"
                        class="block text-sm font-medium text-gray-700"
                        >Selectează categoria</label
                    >
                    <select
                        v-model="formData.category"
                        id="category"
                        class="input"
                    >
                        <option
                            v-for="(category, index) in categories"
                            :key="index"
                            :value="category"
                        >
                            {{ category }}
                        </option>
                    </select>
                </div>
                <!-- Checkbox pentru recurență -->
                <div class="mt-2">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            v-model="formData.is_recurring"
                        />
                        <span class="ml-2 text-sm font-medium text-gray-700">
                            Eveniment recurent
                        </span>
                    </label>
                </div>

                <!-- Radio buttons vizibile DOAR dacă e bifat -->
                <div v-if="formData.is_recurring" class="mt-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Interval de recurență
                    </label>
                    <div class="flex gap-4 mt-2">
                        <label
                            class="flex items-center"
                            :class="{ 'opacity-50': eventDuration > 7 }"
                        >
                            <input
                                type="radio"
                                v-model="formData.recurring_interval"
                                value="weekly"
                                class="mr-2"
                                :disabled="eventDuration > 7"
                            />
                            Săptămânal
                        </label>
                        <label
                            class="flex items-center"
                            :class="{ 'opacity-50': eventDuration > 28 }"
                        >
                            <input
                                type="radio"
                                v-model="formData.recurring_interval"
                                value="monthly"
                                class="mr-2"
                                :disabled="eventDuration > 28"
                            />
                            Lunar
                        </label>
                    </div>
                </div>

                <div class="mt-2">
                    <label
                        for="discount"
                        class="block text-sm font-medium text-gray-700"
                        >Procent reducere</label
                    >
                    <input
                        v-model="formData.discount"
                        type="number"
                        id="discount"
                        placeholder="Introdu procentul reducerii"
                        class="input"
                        min="0"
                        max="100"
                        step="1"
                        required
                    />
                </div>
                <label for="isPublished">
                    <input
                        type="checkbox"
                        id="isPublished"
                        v-model="formData.is_published"
                    />
                    Publicat
                </label>
                <div class="mt-5 sm:mt-6">
                    <button
                        type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    >
                        Trimite
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        calendarEvent: Object,
        categories: Array,
    },
    data() {
        return {
            formData: {
                title: this.calendarEvent.title || "",
                description: this.calendarEvent.description || "",
                start: this.formatDate(this.calendarEvent.start) || "",
                end: this.formatDate(this.calendarEvent.end) || "",
                applyTo: this.calendarEvent.details
                    ? JSON.parse(this.calendarEvent.details).applyTo
                    : "all",
                category: this.calendarEvent.details
                    ? JSON.parse(this.calendarEvent.details).category
                    : "",
                discount: this.calendarEvent.details
                    ? JSON.parse(this.calendarEvent.details).discount
                    : 0,
                is_published: this.calendarEvent.is_published || false,
                is_recurring: !!this.calendarEvent.is_recurring,
                recurring_interval:
                    this.calendarEvent?.recurring_interval ?? null,
            },
            startTimeMin: new Date().toISOString().split("T")[0] + "T00:00",
        };
    },
    methods: {
        formatDate(dateTime) {
            if (!dateTime) return null;

            if (dateTime.includes("T")) {
                return dateTime.replace("T", " ");
            }

            return dateTime;
        },

        closeForm() {
            this.$emit("closeForm");
        },

        submitForm() {
            const details = {
                applyTo: this.formData.applyTo,
                category: this.formData.category,
                discount: this.formData.discount,
            };

            this.formData.details = JSON.stringify(details);
            this.formData.start = this.formatDate(this.formData.start);
            this.formData.end = this.formatDate(this.formData.end);

            this.$inertia.put(
                route("admin.calendar.event.update", {
                    id: this.calendarEvent.id,
                }),
                {
                    payload: this.formData,
                }
            );
            this.$emit("formSubmitted");
        },
    },
    computed: {
        eventDuration() {
            if (!this.formData.start || !this.formData.end) return 0;
            const start = new Date(this.formData.start);
            const end = new Date(this.formData.end);
            const diff = Math.abs(end - start);
            return Math.ceil(diff / (1000 * 60 * 60 * 24)); // zile
        },
    },
};
</script>

<style scoped>
.input {
    width: 100%;
    padding: 8px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
