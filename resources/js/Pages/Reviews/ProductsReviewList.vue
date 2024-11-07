<template>
   
    <div v-if="isLoggedIn()" class="mt-10">
        <div v-if="authUserHasRole('User')">
            <button @click="toggleReviewForm"
                class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ showReviewForm ? 'Cancel' : 'Add Review' }}
            </button>
        </div>

        <!-- Form for Adding New Review -->
        <div v-if="showReviewForm" class="mt-6 mb-24">
            <ReviewForm :productId="productId" @update:showReviewForm="showReviewForm = $event" />
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
        <div v-if="message"
            class="bg-yellow-100 mt-16 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg mb-6">
            {{ message }}
        </div>

        <div v-if="reviews.length > 0">
            <div v-for="(review, reviewIdx) in reviews" :key="review.id" class="text-sm text-gray-500">
                <div :class="[reviewIdx === 0 ? '' : 'border-t border-gray-200', 'py-10']">

                    <div class="flex items-start gap-32 border p-6 rounded-lg shadow-md bg-white">
                        <inertia-link class="flex flex-col items-center"
                            :href="route('user.profile.show', { userId: review.user.id })">
                            <img v-if="review.user.gender === 'Male'" src="/images/male.png" alt="User Avatar"
                                class="w-14 h-14 rounded-full border-2 border-indigo-500 shadow-sm" />
                            <img v-else src="/images/female.png" alt="User Avatar"
                                class="w-14 h-14 rounded-full border-2 border-indigo-500 shadow-sm" />
                            <template v-if="review.isVerified">
                                <div class="flex items-center">
                                    <VerifiedSVG></VerifiedSVG>
                                    <div>Verified </div>
                                </div>
                            </template>
                            <template v-else>
                                Not verified
                            </template>
                            <span class="font-medium text-gray-900 mt-2 ">{{ review.user.name }}</span>
                            <p class="text-sm text-gray-600">{{ review.updated_at }}</p>
                        </inertia-link>
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
                                <label for="description"
                                    class=" text-2xl font-semibold text-gray-800">Description:</label>
                                <div class=" text-xl text-gray-800">{{ review.description }}</div>
                            </div>
                            <div v-if="review.reviewMedia.length">
                                <h3>Media:</h3>
                                <ul>
                                    <li v-for="media in review.reviewMedia" :key="media.id">
                                        <img v-if="media.type === 'image'" :src="media.url" alt="Media"
                                            class="w-16 h-auto" />
                                        <video v-if="media.type === 'video'" controls>
                                            <source :src="media.url" type="video/mp4" />
                                        </video>
                                    </li>
                                </ul>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa fa-thumbs-up fa-3x"
                                    @click="review.isLiked ? dislikeReview(review) : likeReview(review)"
                                    :style="{ color: review.isLiked ? '#74C0FC' : 'gray' }" aria-hidden="true"></i>
                                <div>{{ review.likes }}</div>
                            </div>
                            <div class="flex gap-32 ">
                                <div class="flex cursor-pointer">
                                    <ViewCommentSVG></ViewCommentSVG>
                                    <button @click="toggleComment(reviewIdx)">
                                        {{ reviewStates[reviewIdx].isAddCommentOpen ? 'Anuleaza' : 'Adauga un comentariu' }}
                                    </button>
                                </div>
                                <div class="flex cursor-pointer">
                                    <AddCommentSVG></AddCommentSVG>
                                    <button @click="toggleSectionComments(reviewIdx)">
                                        {{ reviewStates[reviewIdx].isCommentSectionOpen ? 'Anuleaza' : `Vezi comentarii
                                        ${review.commentsCount
                                            }` }}

                                    </button>
                                </div>
                            </div>

                            <div v-if="isLoggedIn() && review.user.id === $page.props.user.id"
                                class="flex justify-end mt-4 space-x-2">
                                <button @click="editReview(reviewIdx)"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 shadow-md transition duration-200">
                                    {{ reviewStates[reviewIdx].editReviewForm ? 'Cancel' : 'Edit' }}
                                </button>
                                <button @click="deleteReview(reviewIdx)"
                                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 shadow-md transition duration-200">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <GenericDeleteNotification :open="reviewStates[reviewIdx].isDeleteDialogOpen"
                    @update:open="reviewStates[reviewIdx].isDeleteDialogOpen = $event" title="Remove Review!"
                    message="Are you sure you want to delete this review?" :objectId="review.id" :extraId="productId"
                    :deleteRoute="'products.reviews.destroy'" :items="null">
                </GenericDeleteNotification>

                <div v-if="reviewStates[reviewIdx].editReviewForm" class="mt-6">
                    <ReviewFormUpdate :productId="productId" :review="review" :editMode="editMode"
                        @update:editReviewForm="reviewStates[reviewIdx].editReviewForm = $event" />
                </div>
                <div v-if="reviewStates[reviewIdx].isAddCommentOpen" class="mt-6">
                    <CommentForm :reviewId="review.id" :edit-form="false"></CommentForm>
                </div>
                <div v-if="reviewStates[reviewIdx].isCommentSectionOpen" class="mt-6">
                    <CommentSection :comments="review.comments"></CommentSection>
                </div>
            </div>
        </div>
        <div v-else
            class="bg-yellow-100 mt-16 border border-yellow-300 text-yellow-700 text-center py-4 px-6 rounded-lg mb-6">
            Nu exista review uri la acest produs momentan! Fii primul care da un review!</div>
    </div>
</template>

<script>
import ReviewForm from './ReviewForm.vue';
import StarRating from 'vue-star-rating';
import 'font-awesome/css/font-awesome.css';
import GenericDeleteNotification from '@/Components/GenericDeleteNotification.vue';
import CommentForm from './CommentForm.vue';
import ViewCommentSVG from '@/Components/ViewCommentSVG.vue';
import AddCommentSVG from '@/Components/AddCommentSVG.vue';
import CommentSection from './CommentSection.vue';
import SortingComponent from '@/Components/SortingComponent.vue';
import ReviewFormUpdate from './ReviewFormUpdate.vue';
import VerifiedSVG from '@/Components/VerifiedSVG.vue';
import ReviewSummary from './ReviewSummary.vue';
import NotificationCenter from '../Notification_System/NotificationCenter.vue';


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
            required: true
        },

        productId: {
            type: String,
            required: true
        },
        message: {
            type: String
        },
    },

    data() {
        return {
            showReviewForm: false,
            isLiked: false,
            editMode: false,
            reviewStates: [],
            userId: this.$page.props.user.id,

        }
    },
    mounted() {
    //     console.log('sdbv');
    //     window.Echo.private(`comments.${this.userId}`).listen('.CommentEvent', (event) => {        
    //     console.log(event);
    // });
    },
    watch: {
        reviews: {
            handler() {
                this.initializeReviewStates();
            },
            immediate: true
        }
    },
    methods: {
        initializeReviewStates() {

            this.reviewStates = this.reviews.map(() => ({
                editReviewForm: false,
                isDeleteDialogOpen: false,
                isAddCommentOpen: false,
                isCommentSectionOpen: false
            }));

        },
        toggleReviewForm() {

            this.showReviewForm = !this.showReviewForm;
            console.log(this.showReviewForm);
        },

        editReview(index) {
            this.editMode = true;
            this.reviewStates[index].editReviewForm = !this.reviewStates[index].editReviewForm;
            this.reviewStates[index].isCommentSectionOpen = false;
            this.reviewStates[index].isAddCommentOpen = false;
        },

        deleteReview(index) {
            this.reviewStates[index].isDeleteDialogOpen = true;
        },

        toggleComment(index) {
            this.reviewStates[index].isAddCommentOpen = !this.reviewStates[index].isAddCommentOpen;
            this.reviewStates[index].editReviewForm = false;
            this.reviewStates[index].isCommentSectionOpen = false;
        },

        toggleSectionComments(index) {
            this.reviewStates[index].isCommentSectionOpen = !this.reviewStates[index].isCommentSectionOpen;
            this.reviewStates[index].editReviewForm = false;
            this.reviewStates[index].isAddCommentOpen = false;

        },

        async likeReview(review) {
            await this.$inertia.post(route('reviews.like', review.id), {}, {
                onSuccess: (page) => {

                }
            });
        },

        async dislikeReview(review) {
            await this.$inertia.post(route('reviews.unlike', review.id), {}, {
                onSuccess: (page) => {

                }
            });
        }
    }
}
</script>
