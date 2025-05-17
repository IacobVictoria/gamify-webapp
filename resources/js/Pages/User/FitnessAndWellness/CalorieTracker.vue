<template>
    <div class="min-h-screen flex items-center justify-center py-10 px-4">
        <div class="w-full max-w-3xl p-6">
            <!-- Sex & Age -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 flex items-center"
            >
                <div>
                    <p class="text-lg font-semibold mb-3">
                        Care este sexul tău?
                    </p>
                    <div class="flex justify-center gap-6">
                        <button
                            @click="form.sex = 'male'"
                            :class="{
                                'border-indigo-500 bg-indigo-50':
                                    form.sex === 'male',
                            }"
                            class="w-28 flex flex-col items-center border rounded-lg p-3 hover:border-indigo-500 transition"
                        >
                            <img
                                src="/images/male.png"
                                alt="Male"
                                class="w-10 h-10 mb-2"
                            />
                            <span class="font-medium">Bărbat</span>
                        </button>
                        <button
                            @click="form.sex = 'female'"
                            :class="{
                                'border-indigo-500 bg-indigo-50':
                                    form.sex === 'female',
                            }"
                            class="w-28 flex flex-col items-center border rounded-lg p-3 hover:border-indigo-500 transition"
                        >
                            <img
                                src="/images/female.png"
                                alt="Female"
                                class="w-10 h-10 mb-2"
                            />
                            <span class="font-medium">Femeie</span>
                        </button>
                    </div>
                    <p v-if="errors.sex" class="text-red-500 text-sm mt-2">
                        {{ errors.sex[0] }}
                    </p>
                </div>

                <div>
                    <label for="age" class="block text-lg font-semibold mb-2"
                        >Ce vârstă ai?</label
                    >
                    <div class="flex items-center">
                        <input
                            v-model="form.age"
                            id="age"
                            type="number"
                            placeholder="Introdu vârsta"
                            class="w-full border rounded-lg px-4 py-2 text-center focus:outline-none focus:border-indigo-500"
                        />
                        <span class="ml-2 text-lg">ani</span>
                    </div>
                    <p v-if="errors.age" class="text-red-500 text-sm mt-2">
                        {{ errors.age[0] }}
                    </p>
                </div>
            </div>

            <!-- Height & Weight -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="height" class="block text-lg font-semibold mb-3"
                        >Înălțimea ta</label
                    >
                    <div class="flex items-center">
                        <input
                            v-model="form.height"
                            id="height"
                            type="number"
                            placeholder="Înălțime"
                            class="flex-1 border rounded-l-lg px-4 py-2 text-center focus:outline-none focus:border-indigo-500"
                        />
                        <select
                            v-model="form.heightUnit"
                            class="border rounded-r-lg px-3 py-2 focus:outline-none focus:border-indigo-500 pr-6"
                        >
                            <option value="cm">cm</option>
                            <option value="ft/in">ft/in</option>
                        </select>
                    </div>
                    <p v-if="errors.height" class="text-red-500 text-sm mt-2">
                        {{ errors.height[0] }}
                    </p>
                </div>

                <div>
                    <label for="weight" class="block text-lg font-semibold mb-3"
                        >Greutatea ta</label
                    >
                    <div class="flex items-center">
                        <input
                            v-model="form.weight"
                            id="weight"
                            type="number"
                            placeholder="Greutate"
                            class="flex-1 border rounded-l-lg px-4 py-2 text-center focus:outline-none focus:border-indigo-500"
                        />
                        <select
                            v-model="form.weightUnit"
                            class="border rounded-r-lg px-8 py-2 focus:outline-none focus:border-indigo-500"
                        >
                            <option value="kg">kg</option>
                            <option value="lb">lb</option>
                        </select>
                    </div>
                    <p v-if="errors.weight" class="text-red-500 text-sm mt-2">
                        {{ errors.weight[0] }}
                    </p>
                </div>
            </div>

            <!-- Activity -->
            <div class="mb-8">
                <p class="text-lg font-semibold mb-3">Cât de activ ești?</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <button
                        v-for="(activity, index) in activityLevels"
                        :key="index"
                        @click="form.activity = activity.value"
                        :class="{
                            'border-indigo-500 bg-indigo-50':
                                form.activity === activity.value,
                        }"
                        class="flex flex-col items-center border rounded-lg p-4 hover:border-indigo-500 transition"
                    >
                        <img
                            :src="activity.image"
                            :alt="activity.label"
                            class="w-10 h-10 mb-2"
                        />
                        <span class="text-sm font-medium text-center">{{
                            activity.label
                        }}</span>
                    </button>
                </div>
                <p v-if="errors.activity" class="text-red-500 text-sm mt-2">
                    {{ errors.activity[0] }}
                </p>
            </div>

            <!-- Button -->
            <div class="text-center">
                <button
                    @click="calculateCalories"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition"
                >
                    Calculează necesarul caloric
                </button>
            </div>

            <!-- Result -->
            <div v-if="calorieResult" class="mt-6 text-center">
                <div class="text-lg text-indigo-700 font-bold">
                    🍽️ Necesarul tău ideal: {{ calorieResult.min }} -
                    {{ calorieResult.max }} kcal/zi
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    Sfat: Consultă asistentul AI pentru recomandări
                    personalizate!
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                sex: null,
                age: "",
                height: "",
                heightUnit: "cm",
                weight: "",
                weightUnit: "kg",
                activity: null,
            },
            errors: {},
            calorieResult: null,
            activityLevels: [
                {
                    value: "lightly_active",
                    label: "Ușor activ",
                    image: "/images/light_active.png",
                },
                {
                    value: "moderately_active",
                    label: "Moderat activ",
                    image: "/images/moderate_active.png",
                },
                {
                    value: "active",
                    label: "Activ",
                    image: "/images/active.png",
                },
                {
                    value: "very_active",
                    label: "Foarte activ",
                    image: "/images/very_active.png",
                },
            ],
        };
    },
    methods: {
        calculateCalories() {
            axios
                .post("/user/wellness/count_calories", this.form)
                .then((response) => {
                    this.calorieResult = response.data;
                    this.errors = {};
                })
                .catch((error) => {
                    if (error.response && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error("Unexpected error:", error);
                    }
                });
        },
    },
};
</script>

<style scoped>
button {
    transition: transform 0.2s;
}

button:hover {
    transform: scale(1.05);
}
</style>
