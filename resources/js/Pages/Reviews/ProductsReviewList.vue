<template>
    <div class="mb-24">
        <div v-if="isLoggedIn()" class="mt-10">
            <div v-if="authUserHasRole('User') && isVerifiedBuyer">
                <button
                    @click="toggleReviewForm"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ showReviewForm ? "Cancel" : "Add Review" }}
                </button>
            </div>
            <div
                v-else
                class="mt-10 bg-gray-100 p-6 rounded-lg shadow-lg text-center"
            >
                Trebuie să fi achiziționat produsul pentru a putea lăsa o
                recenzie!
            </div>

            <!-- Form for Adding New Review -->
            <div v-if="showReviewForm" class="mt-6 mb-24">
                <ReviewForm
                    :productId="productId"
                    @update:showReviewForm="showReviewForm = $event"
                />
            </div>
        </div>
        <div
            v-else
            class="mt-10 bg-gray-100 p-6 rounded-lg shadow-lg text-center"
        >
            <div class="text-gray-700 text-lg font-semibold mb-4">
                Trebuie să fii logat ca să dai review!
            </div>
            <inertia-link
                :href="route('login')"
                class="no-underline inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Loghează-te
            </inertia-link>
        </div>

        <div class="-mb-10">
            <div
                v-if="message"
                class="bg-yellow-100 mt-16 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg mb-6"
            >
                {{ message }}
            </div>

            <div v-if="reviews.length > 0">
                <div
                    v-for="(review, reviewIdx) in reviews"
                    :key="review.id"
                    class="text-sm text-gray-500"
                >
                    <div
                        :class="[
                            reviewIdx === 0 ? '' : 'border-t border-gray-300',
                            'py-3',
                        ]"
                    >
                        <div
                            class="flex items-start gap-3 p-2 rounded-lg shadow-md bg-white border border-gray-300"
                        >
                            <inertia-link
                                class="flex flex-col items-center text-center no-underline"
                                :href="
                                    route('user.profile.show', {
                                        userId: review.user.id,
                                    })
                                "
                            >
                                <img
                                    :src="
                                        review.user.gender === 'Male'
                                            ? '/images/male.png'
                                            : '/images/female.png'
                                    "
                                    alt="User Avatar"
                                    class="w-14 h-14 rounded-full border-2 border-indigo-500 shadow-md"
                                />

                                <div class="flex items-center text-base mt-1">
                                    <i
                                        v-if="review.isVerified"
                                        class="fa fa-shield-check text-green-500 text-xl"
                                    ></i>
                                    <span
                                        class="text-gray-800 ml-2 font-semibold"
                                    >
                                        {{
                                            review.isVerified
                                                ? "✔️ Verificat"
                                                : "❌ Neconfirmat"
                                        }}
                                    </span>
                                </div>

                                <span
                                    class="font-semibold text-gray-900 text-base mt-2"
                                    >{{ review.user.name }}</span
                                >
                                <p class="text-sm text-gray-600">
                                    {{ review.updated_at }}
                                </p>
                            </inertia-link>

                            <div class="flex-1 items-center">
                                <div class="flex-1">
                                    <div class="flex flex-col items-center">
                                        <star-rating
                                            :star-size="22"
                                            :rating="review.rating"
                                            :read-only="true"
                                            :increment="0.5"
                                            :show-rating="false"
                                            class="mt-2"
                                        />
                                        <p
                                            class="text-gray-900 text-base mt-1 font-bold"
                                        >
                                            ⭐ {{ review.rating }} / 5
                                        </p>
                                    </div>
                                </div>
                                <!-- Titlu și Descriere -->
                                <div class="mt-2 text-base">
                                    <p
                                        class="font-semibold text-gray-900 flex items-center text-xl"
                                    >
                                        <i
                                            class="fa fa-quote-left text-indigo-500 mr-2 text-xl"
                                        ></i>
                                        {{ review.title }}
                                    </p>
                                    <p class="text-gray-800 text-base mt-1">
                                        📝 {{ review.description }}
                                    </p>
                                </div>

                                <!-- Media Attachments -->
                                <div
                                    v-if="review.reviewMedia.length"
                                    class="mt-2"
                                >
                                    <h3
                                        class="text-base font-semibold text-gray-900"
                                    >
                                        📷 Media:
                                    </h3>
                                    <div class="flex gap-2 mt-1">
                                        <template
                                            v-for="media in review.reviewMedia"
                                            :key="media.id"
                                        >
                                            <img
                                                v-if="media.type === 'image'"
                                                :src="media.url"
                                                alt="Media"
                                                class="w-16 h-16 rounded shadow-md border border-gray-400"
                                            />
                                            <video
                                                v-if="media.type === 'video'"
                                                controls
                                                class="w-16 h-16 border border-gray-400"
                                            >
                                                <source
                                                    :src="media.url"
                                                    type="video/mp4"
                                                />
                                            </video>
                                        </template>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-between items-center mt-3"
                                >
                                    <div class="flex items-center gap-3">
                                        <i
                                            class="fa fa-thumbs-up fa-3x"
                                            @click="
                                                !(
                                                    isLoggedIn() &&
                                                    review.user.id !==
                                                        $page.props.user?.id
                                                )
                                                    ? null
                                                    : review.isLiked
                                                    ? dislikeReview(review)
                                                    : likeReview(review)
                                            "
                                            :class="[
                                                !isLoggedIn() ||
                                                review.user.id ===
                                                    $page.props.user?.id
                                                    ? 'text-gray-300 cursor-not-allowed'
                                                    : review.isLiked
                                                    ? 'text-blue-400'
                                                    : 'text-gray-500',
                                            ]"
                                            aria-hidden="true"
                                        ></i>
                                        <div>{{ review.likes }}</div>
                                    </div>

                                    <div class="flex gap-32">
                                        <div class="flex items-center gap-3">
                                            <button
                                                @click="
                                                    toggleComment(reviewIdx)
                                                "
                                                class="text-indigo-500 text-base flex items-center gap-2 hover:text-indigo-700 transition"
                                            >
                                                💬
                                                {{
                                                    reviewStates[reviewIdx]
                                                        .isAddCommentOpen
                                                        ? "Anulează"
                                                        : "Comentează"
                                                }}
                                            </button>
                                            <button
                                                @click="
                                                    toggleSectionComments(
                                                        reviewIdx
                                                    )
                                                "
                                                class="text-indigo-500 text-base flex items-center gap-2 hover:text-indigo-700 transition"
                                            >
                                                👀
                                                {{
                                                    reviewStates[reviewIdx]
                                                        .isCommentSectionOpen
                                                        ? "Anulează"
                                                        : `Comentarii (${review.commentsCount})`
                                                }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="
                                        isLoggedIn() &&
                                        review.user.id === $page.props.user.id
                                    "
                                    class="flex justify-end mt-3 gap-2"
                                >
                                    <button
                                        @click="editReview(reviewIdx)"
                                        class="text-base bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 shadow-md transition"
                                    >
                                        ✏️
                                        {{
                                            reviewStates[reviewIdx]
                                                .editReviewForm
                                                ? "Anulează"
                                                : "Editează"
                                        }}
                                    </button>
                                    <button
                                        @click="deleteReview(reviewIdx)"
                                        class="text-base bg-red-600 text-white px-3 py-1.5 rounded-lg hover:bg-red-700 shadow-md transition"
                                    >
                                        🗑️ Șterge
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <GenericDeleteNotification
                        :open="reviewStates[reviewIdx].isDeleteDialogOpen"
                        @update:open="
                            reviewStates[reviewIdx].isDeleteDialogOpen = $event
                        "
                        title="Remove Review!"
                        message="Are you sure you want to delete this review?"
                        :objectId="review.id"
                        :extraId="productId"
                        :deleteRoute="'products.reviews.destroy'"
                        :items="null"
                    >
                    </GenericDeleteNotification>

                    <div
                        v-if="reviewStates[reviewIdx].editReviewForm"
                        class="mt-6"
                    >
                        <ReviewFormUpdate
                            :productId="productId"
                            :review="review"
                            :editMode="editMode"
                            @update:editReviewForm="
                                reviewStates[reviewIdx].editReviewForm = $event
                            "
                        />
                    </div>
                    <div
                        v-if="reviewStates[reviewIdx].isAddCommentOpen"
                        class="mt-6"
                    >
                        <CommentForm
                            :reviewId="review.id"
                            :edit-form="false"
                        ></CommentForm>
                    </div>
                    <div
                        v-if="reviewStates[reviewIdx].isCommentSectionOpen"
                        class="mt-6"
                    >
                        <CommentSection
                            :comments="review.comments"
                        ></CommentSection>
                    </div>
                </div>
            </div>
            <div
                v-if="reviews.length === 0 && message === ''"
                class="bg-yellow-100 mt-16 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg mb-6"
            >
                Nu exista review uri la acest produs momentan! Fii primul care
                da un review!
            </div>
        </div>
    </div>
</template>

<script>
import ReviewForm from "./ReviewForm.vue";
import StarRating from "vue-star-rating";
import "font-awesome/css/font-awesome.css";
import GenericDeleteNotification from "@/Components/GenericDeleteNotification.vue";
import CommentForm from "./CommentForm.vue";
import ViewCommentSVG from "@/Components/ViewCommentSVG.vue";
import AddCommentSVG from "@/Components/AddCommentSVG.vue";
import CommentSection from "./CommentSection.vue";
import SortingComponent from "@/Components/SortingComponent.vue";
import ReviewFormUpdate from "./ReviewFormUpdate.vue";
import VerifiedSVG from "@/Components/VerifiedSVG.vue";
import ReviewSummary from "./ReviewSummary.vue";
import NotificationCenter from "../Notification_System/NotificationCenter.vue";

export default {
    components: {
        ReviewForm,
        StarRating,
        GenericDeleteNotification,
        ViewCommentSVG,
        CommentForm,
        AddCommentSVG,
        CommentSection,
        SortingComponent,
        ReviewFormUpdate,
        VerifiedSVG,
        ReviewSummary,
    },
    props: {
        reviews: {
            type: Array,
            required: true,
        },

        productId: {
            type: String,
            required: true,
        },
        message: {
            type: String,
        },
        isVerifiedBuyer: {
            type: Boolean,
        },
    },

    data() {
        return {
            showReviewForm: false,
            isLiked: false,
            editMode: false,
            reviewStates: [],
        };
    },

    watch: {
        reviews: {
            handler() {
                this.initializeReviewStates();
            },
            immediate: true,
        },
    },
    methods: {
        initializeReviewStates() {
            this.reviewStates = this.reviews.map(() => ({
                editReviewForm: false,
                isDeleteDialogOpen: false,
                isAddCommentOpen: false,
                isCommentSectionOpen: false,
            }));
        },
        toggleReviewForm() {
            this.showReviewForm = !this.showReviewForm;
            console.log(this.showReviewForm);
        },

        editReview(index) {
            this.editMode = true;
            this.reviewStates[index].editReviewForm =
                !this.reviewStates[index].editReviewForm;
            this.reviewStates[index].isCommentSectionOpen = false;
            this.reviewStates[index].isAddCommentOpen = false;
        },

        deleteReview(index) {
            this.reviewStates[index].isDeleteDialogOpen = true;
        },

        toggleComment(index) {
            this.reviewStates[index].isAddCommentOpen =
                !this.reviewStates[index].isAddCommentOpen;
            this.reviewStates[index].editReviewForm = false;
            this.reviewStates[index].isCommentSectionOpen = false;
        },

        toggleSectionComments(index) {
            this.reviewStates[index].isCommentSectionOpen =
                !this.reviewStates[index].isCommentSectionOpen;
            this.reviewStates[index].editReviewForm = false;
            this.reviewStates[index].isAddCommentOpen = false;
        },

        async likeReview(review) {
            await this.$inertia.post(
                route("reviews.like", review.id),
                {},
                {
                    preserveScroll: true,
                    preserveState: true,
                }
            );
        },

        async dislikeReview(review) {
            await this.$inertia.post(
                route("reviews.unlike", review.id),
                {},
                {
                    preserveScroll: true,
                    preserveState: true,
                }
            );
        },
    },
};
</script>
