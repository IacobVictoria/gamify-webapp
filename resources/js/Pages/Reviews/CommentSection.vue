<template>
    <div class="mt-10">
        <div class="text-2xl font-bold mb-4">Comments</div>
        <div
            v-if="comments.length > 0"
            class="max-h-screen overflow-y-auto border border-gray-200 rounded-lg p-4"
        >
            <div
                v-for="(comment, commentIdx) in comments"
                :key="comment.id"
                class="text-sm text-gray-500"
            >
                <div
                    :class="[
                        commentIdx === 0 ? '' : 'border-t border-gray-300',
                        'py-2',
                    ]"
                >
                    <div class="flex items-start gap-3 p-3">
                        <!-- Profil utilizator -->
                        <inertia-link
                            class="flex flex-col items-center text-center no-underline"
                            :href="
                                route('user.profile.show', {
                                    userId: comment.user.id,
                                })
                            "
                        >
                            <img
                                :src="
                                    comment.user.gender === 'Male'
                                        ? '/images/male.png'
                                        : '/images/female.png'
                                "
                                alt="User Avatar"
                                class="w-12 h-12 rounded-full border-2 border-indigo-500 shadow-md"
                            />

                            <span
                                class="font-semibold text-gray-900 text-sm mt-1"
                                >{{ comment.user.name }}</span
                            >
                            <p class="text-xs text-gray-500">
                                {{ comment.updated_at }}
                            </p>

                            <div
                                v-if="comment.user.role.name === 'Admin'"
                                class="text-red-600 text-xs font-bold mt-1 bg-red-100 px-2 py-1 rounded-full"
                            >
                                👑 Admin
                            </div>
                        </inertia-link>

                        <!-- Conținut Comentariu -->
                        <div class="flex-1">
                            <div class="mt-1 text-sm">
                                <p
                                    class="font-semibold text-gray-900 flex items-center"
                                >
                                    💬 {{ comment.description }}
                                </p>
                            </div>

                            <!-- Like Button -->
                            <div class="flex items-center gap-3 mt-2">
                                <i
                                    class="fa fa-thumbs-up fa-2x"
                                    @click="
                                        !(
                                            isLoggedIn() &&
                                            comment.user.id !== $page.props.user?.id
                                        )
                                            ? null
                                            : comment.isLiked
                                            ? dislikeComment(comment)
                                            : likeComment(comment)
                                    "
                                    :class="[
                                        !isLoggedIn() ||
                                        comment.user.id === $page.props.user?.id
                                            ? 'text-gray-300 cursor-not-allowed'
                                            : comment.isLiked
                                            ? 'text-blue-500'
                                            : 'text-gray-500',
                                    ]"
                                ></i>
                                <span
                                    class="text-base text-gray-800 font-semibold"
                                >
                                    {{ comment.likes }}
                                </span>
                            </div>
                        </div>

                        <!-- Butoane Edit/Delete -->
                        <div
                            v-if="
                                isLoggedIn() &&
                                comment.user.id === $page.props.user.id
                            "
                            class="flex flex-col items-end text-xs mt-1"
                        >
                            <button
                                @click="editComment(commentIdx)"
                                class="text-indigo-600 text-sm hover:text-indigo-800 transition"
                            >
                                ✏️
                                {{
                                    commentStates[commentIdx].editCommentForm
                                        ? "Anulează"
                                        : "Editează"
                                }}
                            </button>
                            <button
                                @click="deleteComment(commentIdx)"
                                class="text-red-600 text-sm hover:text-red-800 transition mt-1"
                            >
                                🗑️ Șterge
                            </button>
                        </div>
                    </div>
                </div>

                <GenericDeleteNotification
                    :open="commentStates[commentIdx].isDeleteDialogOpen"
                    @update:open="
                        commentStates[commentIdx].isDeleteDialogOpen = $event
                    "
                    title="Remove Comment!"
                    message="Are you sure you want to delete this comment?"
                    :objectId="comment.id"
                    :deleteRoute="'review_comments.destroy'"
                >
                </GenericDeleteNotification>

                <div
                    v-if="commentStates[commentIdx].editCommentForm"
                    class="mt-6"
                >
                    <CommentForm
                        @close="closeEditForm(commentIdx)"
                        :editForm="this.editForm"
                        :reviewId="comment.review_id"
                        :comment="comment"
                    ></CommentForm>
                </div>
            </div>
        </div>
        <div v-else>Nu există comentarii! Fii primul care comentează!</div>
    </div>
</template>

<script>
import GenericDeleteNotification from "@/Components/GenericDeleteNotification.vue";
import CommentForm from "./CommentForm.vue";
import ReviewForm from "./ReviewForm.vue";

export default {
    components: {
        GenericDeleteNotification,
        CommentForm,
    },

    data() {
        return {
            editForm: false,
            commentStates: this.comments.map(() => ({
                editCommentForm: false,
                isDeleteDialogOpen: false,
            })),
        };
    },

    props: {
        comments: {
            type: Array,
            required: true,
        },
    },

    methods: {
        editComment(index) {
            this.commentStates[index].editCommentForm =
                !this.commentStates[index].editCommentForm;
            this.editForm = true;
        },

        deleteComment(index) {
            this.commentStates[index].isDeleteDialogOpen = true;
        },
        closeEditForm(index) {
            this.commentStates[index].editCommentForm = false;
        },
        async dislikeComment(comment) {
            await this.$inertia.post(
                route("review_comment.unlike", comment.id),
                {},
                {
                    onSuccess: (page) => {},
                }
            );
        },
        async likeComment(comment) {
            await this.$inertia.post(
                route("review_comment.like", comment.id),
                {},
                {
                    onSuccess: (page) => {},
                }
            );
        },
    },
};
</script>
