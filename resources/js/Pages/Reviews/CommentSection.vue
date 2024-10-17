<template>
    <div class="mt-10">
        <div class="text-2xl font-bold mb-4">Comments</div>
        <div class="max-h-screen overflow-y-auto border border-gray-200 rounded-lg p-4">
            <div v-for="(comment, commentIdx) in comments" :key="comment.id" class="text-sm text-gray-500">
                <div :class="[commentIdx === 0 ? '' : 'border-t border-gray-200', 'py-10']">
                    <div class="flex items-start gap-16">
                        <inertia-link class="flex flex-col items-center"
                            :href="route('user.profile.show', { userId: comment.user.id })">
                            <img v-if="comment.user.gender === 'Male'" src="/images/male.png" alt="User Avatar"
                                class="w-14 h-14 rounded-full border-2 border-indigo-500 shadow-sm" />
                            <img v-else src="/images/female.png" alt="User Avatar"
                                class="w-14 h-14 rounded-full border-2 border-indigo-500 shadow-sm" />
                            <span class="font-medium text-gray-900 mt-2 ">{{ comment.user.name }}</span>
                            <p class="text-sm text-gray-600">{{ comment.updated_at }}</p>
                            <div v-if="comment.user.role.name === 'Admin'" class="text-red-600">Admin</div>
                        </inertia-link>
                        <div class="flex-1 items-center">
                            <div class="flex gap-2 mt-2">
                                <label for="description"
                                    class=" text-base font-semibold text-gray-800">Description:</label>
                                <div class=" text-base text-gray-800">{{ comment.description }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa fa-thumbs-up fa-3x"
                                    @click="comment.isLiked ? dislikeComment(comment) : likeComment(comment)"
                                    :style="{ color: comment.isLiked ? '#74C0FC' : 'gray' }" aria-hidden="true"></i>
                                <div>{{ comment.likes }}</div>
                            </div>
                        </div>
                        <div v-if="isLoggedIn() && comment.user.id === $page.props.user.id" class="mt-4">
                            <button @click="editComment(commentIdx)" class="text-indigo-600 hover:underline">
                                {{ commentStates[commentIdx].editCommentForm ? 'Cancel Edit' : 'Edit' }}
                            </button>
                            <button @click="deleteComment(commentIdx)" class="text-red-600 hover:underline ml-2">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
                <GenericDeleteNotification :open="commentStates[commentIdx].isDeleteDialogOpen"
                    @update:open="commentStates[commentIdx].isDeleteDialogOpen = $event" title="Remove Comment!"
                    message="Are you sure you want to delete this comment?" :objectId="comment.id"
                    :deleteRoute="'review_comments.destroy'">
                </GenericDeleteNotification>

                <div v-if="commentStates[commentIdx].editCommentForm" class="mt-6">
                    <CommentForm @close="closeEditForm(commentIdx)" :editForm="this.editForm"
                        :reviewId="comment.review_id" :comment="comment"></CommentForm>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import GenericDeleteNotification from '@/Components/GenericDeleteNotification.vue';
import CommentForm from './CommentForm.vue';
import ReviewForm from './ReviewForm.vue';

export default {
    components: {
        GenericDeleteNotification,
        CommentForm
    },

    data() {
        return {
            editForm: false,
            commentStates: this.comments.map(() => ({
                editCommentForm: false,
                isDeleteDialogOpen: false,

            })),
        }
    },

    props: {
        comments: {
            type: Array,
            required: true
        }
    },

    methods: {
        editComment(index) {
            this.commentStates[index].editCommentForm = !this.commentStates[index].editCommentForm;
            this.editForm = true;

        },

        deleteComment(index) {
            this.commentStates[index].isDeleteDialogOpen = true;
        },
        closeEditForm(index) {
            this.commentStates[index].editCommentForm = false;
        },
        async dislikeComment(comment) {
            await this.$inertia.post(route('review_comment.unlike', comment.id), {}, {
                onSuccess: (page) => {

                }
            });
        },
        async likeComment(comment) {
            await this.$inertia.post(route('review_comment.like', comment.id), {}, {
                onSuccess: (page) => {
           
                }
            });
        }
    }
}
</script>