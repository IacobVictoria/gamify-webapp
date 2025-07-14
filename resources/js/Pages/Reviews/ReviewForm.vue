<template>
    <form @submit.prevent="submitReview">
        <div class="mb-4">
            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
            <star-rating :star-size="30" v-model:rating="form.rating" :increment="0.5" :show-rating="false"
                :read-only="false" class="mt-1 block" />
            <span v-if="form.errors.rating" class="text-red-600">{{ form.errors.rating }}</span>
        </div>

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Titlul</label>

            <input id="title" v-model="form.title" type="text"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" />

            <span v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</span>

            <div class="mt-2 text-gray-600">Titlu recomandat:</div>

            <button type="button" @click="updateTitle()"
                class="mt-2 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-gray-300 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                {{ recommendedTitle }}
            </button>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Descriere</label>
            <textarea id="description" v-model="form.description"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            <span v-if="form.errors.description" class="text-red-600">{{ form.errors.description }}</span>
        </div>

        <div class="mb-4">
            <label for="media" class="block text-sm font-medium text-gray-700">Media</label>

            <input type="file" name="media" key="media_file" ref="media_file" @change="handleMediaUpload"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
        </div>
        <div>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Trimite
            </button>
        </div>
    </form>

</template>
<script>
import { useForm } from '@inertiajs/vue3';
import StarRating from 'vue-star-rating'

export default {
    components: {
        StarRating
    },
    props: {
        productId: String,
    },
    data() {
        return {
            form: useForm({
                title: '',
                description: '',
                rating: 0,
                media: [],
            }),
            deletedMediaIds: [],
        };
    },
    computed: {
        recommendedTitle() {
            return this.getRatingTitle(this.form.rating);
        },
    },

    emits: ['update:showReviewForm'],

    methods: {
        getRatingTitle(rating) {
            switch (rating) {
                case 0:
                    return 'Dezamăgitor';
                case 0.5:
                    return 'Foarte slab';
                case 1:
                    return 'Rău';
                case 1.5:
                    return 'Acceptabil';
                case 2:
                    return 'Bun';
                case 2.5:
                    return 'Foarte bun';
                case 3:
                    return 'Excelent';
                case 3.5:
                    return 'Impecabil';
                case 4:
                    return 'Extraordinar';
                case 4.5:
                    return 'Perfect';
                case 5:
                    return 'Excelent';
                default:
                    return 'N/A';
            }
        },
        async submitReview() {

            const formData = new FormData();

            formData.append('title', this.form.title);
            formData.append('description', this.form.description);
            formData.append('rating', this.form.rating);

            if (this.form.media) {
                formData.append('media_file', this.form.media);
            }

            this.form.post(this.route('products.reviews.store', { id: this.productId }), {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                data: formData, 
                onSuccess: () => {
                    this.form.reset();
                    this.updateShowReviewForm(false);
                    console.log('succes');
                }
            });


        },
        updateShowReviewForm(newValue) {
            this.$emit('update:showReviewForm', newValue);
        },

        updateTitle() {
            this.form.title = this.recommendedTitle;
        },

        handleMediaUpload(event) {
            const file = event.target.files[0];
            this.form.media = file;
        },

    },

};
</script>