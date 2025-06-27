<template>
    <div v-if="command" class="favorite-details">
        <h3 class="text-lg font-semibold">
            {{ type === "Comenzi" ? "Detalii Comandă" : "Detalii reducere" }}
        </h3>

        <!-- Comenzi -->
        <template v-if="type === 'Comenzi'">
            <p><strong>Titlu:</strong> {{ command.title }}</p>
            <p><strong>Furnizor:</strong> {{ command.details.supplierName }}</p>
            <h4 class="font-semibold mt-3">Produse:</h4>
            <ul>
                <li
                    v-for="(item, index) in command.details.productQuantities"
                    :key="index"
                >
                    {{ item.productName }}: {{ item.quantity }}
                </li>
            </ul>

            <div class="mt-2">
                <label
                    for="startDate"
                    class="block text-sm font-medium text-gray-700"
                    >Dată Eveniment</label
                >
                <input
                    v-model="startDate"
                    type="date"
                    id="startDate"
                    class="input mt-2"
                    :min="today"
                />
            </div>
        </template>

        <!-- Reduceri -->
        <template v-if="type === 'Discount-uri'">
            <p>
                <strong>Se aplică la:</strong>
                {{
                    command.details.applyTo === "all"
                        ? "Toate produsele"
                        : "Categorie"
                }}
            </p>
            <p v-if="command.details.applyTo === 'categories'">
                <strong>Categorie:</strong> {{ command.details.category }}
            </p>
            <p><strong>Reducere:</strong> {{ command.details.discount }}%</p>

            <div>
                <label
                    for="startTime"
                    class="block text-sm font-medium text-gray-700"
                    >Ora de început</label
                >
                <input
                    v-model="startDateTime"
                    type="datetime-local"
                    id="startTime"
                    class="input"
                    :min="now"
                />
            </div>

            <div>
                <label
                    for="endTime"
                    class="block text-sm font-medium text-gray-700"
                    >Ora de final</label
                >
                <input
                    v-model="endDateTime"
                    type="datetime-local"
                    id="endTime"
                    class="input"
                    :min="startDateTime"
                />
            </div>
        </template>

        <!-- recurență -->
        <div class="mt-2">
            <label class="flex items-center">
                <input type="checkbox" v-model="isRecurring" />
                <span class="ml-2 text-sm font-medium text-gray-700"
                    >Eveniment recurent</span
                >
            </label>
        </div>

        <!-- interval recurență -->
        <div v-if="isRecurring" class="mt-2">
            <label class="block text-sm font-medium text-gray-700"
                >Interval de recurență</label
            >
            <div class="flex gap-4 mt-2">
                <label class="flex items-center">
                    <input
                        type="radio"
                        v-model="recurringInterval"
                        value="weekly"
                        class="mr-2"
                    />
                    Săptămânal
                </label>
                <label class="flex items-center">
                    <input
                        type="radio"
                        v-model="recurringInterval"
                        value="monthly"
                        class="mr-2"
                    />
                    Lunar
                </label>
            </div>
        </div>

        <button class="btn-submit mt-3" @click="updateCommand">Aplică!</button>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

const today = ref(new Date().toISOString().split("T")[0]);
const now = ref(new Date().toISOString().slice(0, 16));

const props = defineProps({
    command: Object,
    initialDate: String,
    type: String,
});

const isRecurring = ref(false);
const recurringInterval = ref(null);

const startDate = ref(props.initialDate);
const startDateTime = ref("");
const endDateTime = ref("");

watch(
    () => props.command,
    (newCommand) => {
        if (newCommand) {
            startDate.value = newCommand.start;
            startDateTime.value = newCommand.start;
            endDateTime.value = newCommand.end;
        }
    }
);

const emit = defineEmits(["close"]);

function formatDate(dateTime) {
    if (!dateTime) return null;

    if (dateTime.includes("T")) {
        return dateTime.replace("T", " ");
    }

    return dateTime;
}

const updateCommand = () => {
    const updatedDetails = {
        ...props.command.details,
        fromFavorites: true,
    };

    const payload = {
        title: props.command.title,
        description: props.command.description || "",
        start:
            props.type === "Comenzi"
                ? startDate.value
                : formatDate(startDateTime.value),
        end:
            props.type === "Comenzi"
                ? startDate.value
                : formatDate(endDateTime.value),
        details: JSON.stringify(updatedDetails),
        ...(props.type === "Comenzi"
            ? {
                  type: "supplier_order",
                  supplierId: props.command.details.supplierId,
                  supplierName: props.command.details.supplierName,
                  productQuantities: props.command.details.productQuantities,
                  calendarId: "personal",
              }
            : {
                  type: "discount",
                  applyTo: props.command.details.applyTo,
                  category: props.command.details.category || "",
                  discount: props.command.details.discount,
                  calendarId: "leisure",
              }),
        is_published: true,
        is_recurring: isRecurring.value,
        recurring_interval: isRecurring.value ? recurringInterval.value : null,
    };

    useForm(payload).post(route("admin.calendar.event.store"), {
        onSuccess: () => {
            window.location.reload();
            emit("close");
        },
        onError: (errors) => {},
    });
};
</script>

<style scoped>
.favorite-details {
    margin-top: 20px;
    background-color: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.input {
    width: 100%;
    padding: 8px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
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
