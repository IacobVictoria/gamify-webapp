<template>
    <form @submit.prevent="submitReview">
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" v-model="form.description"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            <span v-if="form.errors.description" class="text-red-600">{{ form.errors.description }}</span>
        </div>

        <div class="mb-4">
            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
            <select id="rating" v-model="form.rating"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="" disabled>Select rating</option>
                <option v-for="star in [1, 2, 3, 4, 5]" :key="star" :value="star">{{ star }} Star</option>
            </select>
            <span v-if="form.errors.rating" class="text-red-600">{{ form.errors.rating }}</span>
        </div>

        <div>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ editMode ? 'Update Review' : 'Submit Review' }}
            </button>
        </div>
    </form>

</template>
<script>
import { useForm } from '@inertiajs/vue3';

export default {
    props: {
        productId: Number,
        review: Object,
        editMode: Boolean,
    },
    data() {
        return {
            form: useForm({
                description: this.review ? this.review.description : '',
                rating: this.review ? this.review.rating : ''
            }),

        };
    },
    watch: {
        review(newReview) {
            this.form.description = newReview?.description || '';
            this.form.rating = newReview?.rating || '';
        }
    },

    emits: ['update:showReviewForm', 'update:editReviewForm'],

    methods: {
        submitReview() {
            if (this.editMode) {
                this.form.put(this.route('products.reviews.update', { productId: this.productId, reviewId: this.review.id }), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.updateEditReviewForm(false);
                        
                    }
                });
            } else {
                this.form.post(this.route('products.reviews.store', { id: this.productId }), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.updateShowReviewForm(false);
                       

                    }
                });
            }
        },
        updateShowReviewForm(newValue) {
            this.$emit('update:showReviewForm', newValue);
        },

        updateEditReviewForm(newValue) {
            this.$emit('update:editReviewForm', newValue);
        }
    },

};
</script>