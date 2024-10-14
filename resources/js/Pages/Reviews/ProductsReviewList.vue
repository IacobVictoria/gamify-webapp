<template>
    <div v-if="isLoggedIn()" class="mt-10">
        <button @click="toggleReviewForm"
            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ showReviewForm ? 'Cancel' : 'Add Review' }}
        </button>


        <!-- Form for Adding New Review -->
        <div v-if="showReviewForm" class="mt-6 mb-24">
            <ReviewForm :productId="productId" :review="null" :editMode="false"
                @update:showReviewForm="showReviewForm = $event" />
        </div>
    </div>
    <div v-else class="mt-10 bg-gray-100 p-6 rounded-lg shadow-lg text-center">
        <div class="text-gray-700 text-lg font-semibold mb-4">
            You need to be logged in to make a review.
        </div>
        <inertia-link :href="route('login')"
            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Log In
        </inertia-link>
    </div>

    <div class="-mb-10">
        <h3 class="sr-only">Customer Reviews</h3>
        <div v-for="(review, reviewIdx) in reviews" :key="review.id" class="text-sm text-gray-500">
            <div :class="[reviewIdx === 0 ? '' : 'border-t border-gray-200', 'py-10']">

                <div class="flex items-start gap-32 border p-6 rounded-lg shadow-md bg-white">
                    <div class="flex flex-col items-center">
                        <img v-if="review.user.gender === 'Male'" src="/images/male.png" alt="User Avatar"
                            class="w-14 h-14 rounded-full border-2 border-indigo-500 shadow-sm" />
                        <img v-else src="/images/female.png" alt="User Avatar"
                            class="w-14 h-14 rounded-full border-2 border-indigo-500 shadow-sm" />
                        <span class="font-medium text-gray-900 mt-2">{{ review.user.name }}</span>
                        <p class="text-sm text-gray-600">{{ review.updated_at }}</p>
                    </div>
                    <div class="flex-1 items-center">
                        <div class="flex flex-col items-center">
                            <star-rating :star-size="40" :rating="review.rating" :read-only="true" :increment="0.5"
                                :show-rating="false" class="mt-1 block" />

                            <p class="text-gray-700 mt-4 ">{{ review.rating }} out of 5 stars</p>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <label for="title" class=" text-2xl font-semibold text-gray-800">Title:</label>
                            <div class=" text-xl text-gray-800">{{ review.title }}</div>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <label for="description" class=" text-2xl font-semibold text-gray-800">Description:</label>
                            <div class=" text-xl text-gray-800">{{ review.description }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa fa-thumbs-up fa-3x"
                                @click="review.isLiked ? dislikeReview(review) : likeReview(review)"
                                :style="{ color: review.isLiked ? '#74C0FC' : 'gray' }" aria-hidden="true"></i>
                            <div>{{ review.likes }}</div>
                        </div>

                        <div v-if="isLoggedIn() && review.user.id === $page.props.user.id"
                            class="flex justify-end mt-4 space-x-2">
                            <button @click="editReview()"
                                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 shadow-md transition duration-200">
                                {{ editReviewForm ? 'Cancel' : 'Edit' }}
                            </button>
                            <button @click="deleteReview()"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 shadow-md transition duration-200">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <GenericDeleteNotification :open="isDeleteDialogOpen" @update:open="isDeleteDialogOpen = $event"
                title="Remove Review!" message="Are you sure you want to delete this review?" :objectId="review.id"
                :extraId="productId" :deleteRoute="'products.reviews.destroy'" :items="null">
            </GenericDeleteNotification>

            <div v-if="editReviewForm" class="mt-6">
                <ReviewForm :productId="productId" :review="review" :editMode="editMode"
                    @update:editReviewForm="editReviewForm = $event" />
            </div>
        </div>
    </div>
</template>

<script>
import ReviewForm from './ReviewForm.vue';
import StarRating from 'vue-star-rating';
import 'font-awesome/css/font-awesome.css';
import GenericDeleteNotification from '@/Components/GenericDeleteNotification.vue';

export default {
    components: {
        ReviewForm,
        StarRating,
        GenericDeleteNotification
    },

    props: {
        reviews: {
            type: Array,
            required: true
        },

        productId: {
            type: String,
            required: true
        },
    },

    data() {
        return {
            showReviewForm: false,
            editMode: false,
            editReviewForm: false,
            isLiked: false,
            isDeleteDialogOpen: false
        }
    },
    computed: {
        contor() {

        }
    },
    methods: {
        toggleReviewForm() {
            this.showReviewForm = !this.showReviewForm;
        },

        editReview() {
            this.editMode = true;
            this.editReviewForm = !this.editReviewForm;
        },

        deleteReview() {
            this.isDeleteDialogOpen = true;

        },

        async likeReview(review) {
            await this.$inertia.post(route('reviews.like', review.id), {}, {
                onSuccess: (page) => {
                    review.likes = page.props.reviews.find(r => r.id === review.id).likes;
                    review.isLiked = true;
                }
            });
        },

        async dislikeReview(review) {
            await this.$inertia.post(route('reviews.unlike', review.id), {}, {
                onSuccess: (page) => {
                    review.likes = page.props.reviews.find(r => r.id === review.id).likes;
                    review.isLiked = false;
                }
            });
        }
    }
}
</script>
<style>
.liked {
    color: gray;
}
</style>
