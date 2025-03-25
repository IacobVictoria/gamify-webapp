<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <p class="text-base font-semibold leading-6 text-gray-900">
                    {{ description }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="filter in filters" :key="filter.model">
                <Filter
                    :filter="filter"
                    :value="filterValues[filter.model]"
                    @update:value="updateFilter(filter.model, $event)"
                />
            </div>
        </div>

        <!-- Items table -->
        <div
            class="mt-8 overflow-hidden border-b border-gray-200 shadow sm:rounded-lg"
        >
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                    class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                >
                    <div
                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        v-for="column in columns"
                                        :key="column.name"
                                        scope="col"
                                        class="px-6 py-1 text-left text-sm text-gray-400"
                                        :class="column.columnAlign"
                                    >
                                        <div
                                            class="flex flex-row items-center gap-2"
                                        >
                                            {{ column.label }}
                                            <div
                                                class="cursor-pointer hover:rounded-full hover:bg-gray-300"
                                                v-if="column.sorting"
                                                @click="toggleSorting(column)"
                                            >
                                                <ArrowOrderSVG
                                                    :direction="
                                                        currentSortDirection[
                                                            column.name
                                                        ]
                                                    "
                                                    v-if="
                                                        column.sorting === true
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-center px-6 py-3 text-md font-semibold text-gray-500"
                                    >
                                        Acțiuni
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white divide-y divide-gray-200 font-semibold"
                            >
                                <template
                                    v-for="(item, index) in items.data"
                                    :key="item.id"
                                >
                                    <tr>
                                        <td
                                            v-for="column in columns"
                                            :key="column.name"
                                            class="px-6 py-4 lg:whitespace-normal whitespace-nowrap text-sm text-gray-900"
                                            :class="column.valueAlign"
                                        >
                                            <span>{{ item[column.name] }}</span>
                                        </td>

                                        <td
                                            class="flex flex-col items-center text-center px-6 py-4"
                                        >
                                            <div
                                                class="flex gap-8 items-center"
                                            >
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-center text-md font-medium"
                                                >
                                                    <inertia-link
                                                        :href="
                                                            route(
                                                                updateRoute,
                                                                item.id
                                                            )
                                                        "
                                                        class="text-indigo-600 no-underline hover:text-indigo-900"
                                                        >Quiz
                                                        Manager</inertia-link
                                                    >
                                                </td>
                                                <button
                                                    @click="
                                                        toggleDetails(index)
                                                    "
                                                    class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 transition duration-200"
                                                >
                                                    {{
                                                        showDetails[index]
                                                            ? "Ascunde"
                                                            : "Afișează"
                                                    }}
                                                    intrebarile
                                                </button>

                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-center text-md font-medium"
                                                >
                                                    <inertia-link
                                                        :href="
                                                            route(
                                                                'admin-gamification.quiz_remarks.show',
                                                                item.id
                                                            )
                                                        "
                                                        class="text-indigo-600 no-underline hover:text-indigo-900"
                                                        >Quiz
                                                        Feedback</inertia-link
                                                    >
                                                </td>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="showDetails[index]"
                                        class="details-row"
                                    >
                                        <td
                                            colspan="100%"
                                            class="px-6 py-4 bg-gray-50 border-b border-gray-200"
                                        >
                                            <div
                                                v-if="item.questions.length > 0"
                                                class="p-4 bg-gray-50 rounded-lg shadow-md"
                                            >
                                                <!-- Carusel Întrebări -->
                                                <Carousel :items-to-show="1">
                                                    <Slide
                                                        v-for="(
                                                            question, qIndex
                                                        ) in item.questions"
                                                        :key="qIndex"
                                                    >
                                                        <!-- Detalii întrebare -->
                                                        <div
                                                            class="p-4 bg-white rounded shadow-md w-3/6"
                                                        >
                                                            <h3
                                                                class="text-lg font-semibold text-gray-700"
                                                            >
                                                                Întrebarea
                                                                {{
                                                                    qIndex + 1
                                                                }}:
                                                                {{
                                                                    question.question
                                                                }}
                                                            </h3>

                                                            <p
                                                                class="text-sm text-gray-600 mb-2"
                                                            >
                                                                Scor:
                                                                {{
                                                                    question.score
                                                                }}
                                                            </p>

                                                            <!-- Răspunsuri -->
                                                            <button
                                                                @click="
                                                                    toggleAnswers(
                                                                        index,
                                                                        qIndex
                                                                    )
                                                                "
                                                                class="text-blue-500 underline mb-2"
                                                            >
                                                                {{
                                                                    showAnswers[
                                                                        index
                                                                    ][qIndex]
                                                                        ? "Ascunde"
                                                                        : "Afișează"
                                                                }}
                                                                Răspunsuri
                                                            </button>

                                                            <ul
                                                                v-if="
                                                                    showAnswers[
                                                                        index
                                                                    ][qIndex]
                                                                "
                                                                class="space-y-2 mt-2"
                                                            >
                                                                <div
                                                                    v-if="
                                                                        question
                                                                            .answers
                                                                            .length >
                                                                        0
                                                                    "
                                                                >
                                                                    <li
                                                                        v-for="(
                                                                            answer,
                                                                            answerIndex
                                                                        ) in question.answers"
                                                                        :key="
                                                                            answerIndex
                                                                        "
                                                                        class="flex justify-between items-center p-2 mb-2 bg-gray-100 rounded"
                                                                    >
                                                                        <span>{{
                                                                            answer.answer
                                                                        }}</span>

                                                                        <span
                                                                            :class="{
                                                                                'bg-green-400':
                                                                                    answer.is_correct,
                                                                                'bg-red-400':
                                                                                    !answer.is_correct,
                                                                            }"
                                                                            class="w-4 h-4 rounded-full"
                                                                        ></span>
                                                                    </li>
                                                                </div>
                                                                <div
                                                                    v-else
                                                                    class="bg-yellow-100 mt-16 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg mb-6"
                                                                >
                                                                    No questions
                                                                    at the
                                                                    moment! Try
                                                                    the
                                                                    quizManager!
                                                                </div>
                                                            </ul>
                                                        </div>
                                                    </Slide>
                                                    <template #addons>
                                                        <Navigation />
                                                        <Pagination />
                                                    </template>
                                                </Carousel>
                                            </div>

                                            <div
                                                v-else
                                                class="bg-yellow-100 mt-16 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg mb-6"
                                            >
                                                No questions at the moment! Try
                                                the quizManager!
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <GenericDeleteNotification
                            :open="isDeleteDialogOpen"
                            @update:open="isDeleteDialogOpen = $event"
                            title="Delete Item"
                            :message="messageToDelete"
                            :deleteRoute="routeToDelete"
                            :objectId="itemToDelete"
                        />
                    </div>
                </div>
            </div>
        </div>

        <Pagination class="flex justify-center" :links="items.links" />
    </div>
</template>

<script>
import "vue3-carousel/dist/carousel.css";
import Filter from "./Filter.vue";
import GenericDeleteNotification from "./GenericDeleteNotification.vue";
import Pagination from "./Pagination.vue";
import debounce from "lodash/fp/debounce";
import { ref, watch } from "vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";
import ExitSVG from "./ExitSVG.vue";
import ArrowOrderSVG from "./ArrowOrderSVG.vue";

export default {
    name: "GenericList",

    components: {
        Pagination,
        Filter,
        GenericDeleteNotification,
        Carousel,
        Slide,
        Navigation,
        Pagination,
        ExitSVG,
        ArrowOrderSVG,
    },

    props: {
        items: Object,
        title: String,
        description: String,
        columns: Array,
        filters: Array,
        prevFilters: Array,
        entityName: String,
        getRoute: String,
        descriptionDetails: String,
        detailsLabel: Array,
        extraLabel: Array,
        invoice: String,
        updateRoute: String,
        deleteRoute: String,
    },
    data() {
        return {
            filterValues: Object.fromEntries(
                this.filters.map((filter) => [
                    filter.model,
                    this.prevFilters[filter.model] || "",
                ])
            ),
            showDetails: [],
            isDeleteDialogOpen: false,
            itemToDelete: null,
            showAnswers: [],
            routeToDelete: "",
            messageToDelete: "",
            currentSortColumn: null,
            currentSortDirection: {},
        };
    },

    watch: {
        filterValues: {
            handler: debounce(300, function () {
                this.$inertia.get(
                    route(this.getRoute),
                    {
                        filters: this.filterValues,
                    },
                    {
                        preserveState: true,
                        replace: true,
                    }
                );
            }),
            deep: true,
        },
    },
    mounted() {
        this.showDetails = Array(this.items.data.length).fill(false);
        this.showAnswers = this.items.data.map((item) =>
            new Array(item.questions.length).fill(false)
        );
    },

    methods: {
        updateFilter(model, newValue) {
            this.filterValues[model] = newValue;
        },
        toggleSorting(column) {
            if (this.currentSortColumn === column.name) {
                this.currentSortDirection[column.name] =
                    this.currentSortDirection[column.name] === "asc"
                        ? "desc"
                        : "asc";
            } else {
                this.currentSortColumn = column.name;
                this.currentSortDirection[column.name] = "asc";
            }
            this.fetchSortedData();
        },

        fetchSortedData() {
            this.$inertia.get(
                route(this.getRoute, {
                    orderBy: this.currentSortColumn,
                    orderDirection:
                        this.currentSortDirection[this.currentSortColumn],
                }),
                {},
                {
                    preserveState: true,
                    replace: true,
                    preserveScroll: true,
                }
            );
        },

        toggleAnswers(itemIndex, questionIndex) {
            if (!this.showAnswers[itemIndex]) {
                this.showAnswers[itemIndex] = [];
            }
            this.showAnswers[itemIndex][questionIndex] =
                !this.showAnswers[itemIndex][questionIndex];
        },

        toggleDetails(index) {
            this.showDetails[index] = !this.showDetails[index];
            if (!this.showDetails[index]) {
                this.showAnswers[index] = new Array(
                    this.items.data[index].questions.length
                ).fill(false);
            }
        },
        deleteAnswer(answer) {
            this.isDeleteDialogOpen = !this.isDeleteDialogOpen;
            this.itemToDelete = answer.id;
            this.routeToDelete = "admin-gamification.answers.destroy";
            this.messageToDelete =
                "Are you sure you want to delete this answer?";
        },
        deleteQuestion(question) {
            this.isDeleteDialogOpen = !this.isDeleteDialogOpen;
            this.itemToDelete = question.id;
            this.routeToDelete = "admin-gamification.questions.destroy";
            this.messageToDelete =
                "Are you sure you want to delete this question?";
        },
        deleteQuiz(quiz) {
            this.isDeleteDialogOpen = !this.isDeleteDialogOpen;
            this.itemToDelete = quiz.id;
            this.routeToDelete = "admin-gamification.user_quizzes.destroy";
            this.messageToDelete = "Are you sure you want to delete this quiz?";
        },
    },
};
</script>

<style scoped></style>
