<template>
    <template v-if="editForm">
            <GenericEditForm :title="'Update the comment'" :fields="fields"
                :update-route="updateRoute" :initial-data="comment" ></GenericEditForm>
    </template>
    <template v-else>
        <GenericCreateForm :title="'Adauga un comment'" :fields="fields" :create-route="createRoute"
            :objectId="reviewId">
        </GenericCreateForm>
    </template>
</template>

<script>
import GenericCreateForm from '@/Components/GenericCreateForm.vue';
import GenericEditForm from '@/Components/GenericEditForm.vue';

export default {
    components: {
        GenericCreateForm,
        GenericEditForm
    },
    props: {
        reviewId: {
            type: String,
            required: true
        },
        editForm: {
            type: Boolean,
            required: true
        },
        comment: {
            type: Object
        }
    },
    data() {
        return {
            fields: [
                {
                    name: 'description',
                    label: 'Description',
                    type: 'textarea',
                    inputType: 'textarea',
                    autocomplete: 'description',
                    placeholder: 'Enter description',
                    colSpan: 'sm:col-span-6'
                },
            ],
        }
    },
    computed: {
        createRoute() {
            return route('review_comments.store', { reviewId: this.reviewId });
        },
        updateRoute() {
            return route('review_comments.update', { commentId: this.comment.id });
        },

    }
}
</script>