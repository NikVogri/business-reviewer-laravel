<template>
    <li class=" mb-2 rounded review">
        <div class="lg:flex w-full">
            <!-- <x-user-card :user="$review->author" /> -->
            <UserCard :author="reviewData.author" />
            <div class="flex-1 items-between">

                    <StarRating
                        :rating="reviewData.rating"
                        :createdAt="reviewData.created_at"
                        :small="true"
                    />
  
                <p>{{ reviewData.body }}</p>

                <div v-if="reviewData.image">
                    <button
                        type="button"
                        class="underline text-red-600"
                        @click="toggleImageView"
                    >
                        Show Uploaded Image
                    </button>
                    <img
                        v-if="showImage"
                        :src="imageUrl"
                        :alt="reviewData.image.updated_at"
                        class="block w-full"
                    />
                </div>

                <div
                    class="p-4 w-full mt-4 bg-gray-200"
                    v-if="reviewData.reply"
                >
                    <span class="text-gray-600">Owners reply:</span>
                    <p>{{ reviewData.reply.body }}</p>
                </div>

                <div
                    class="mt-6 mt-5"
                    v-if="currentUserIsOwner && !reviewData.reply"
                >
                    <textarea
                        name="body"
                        rows="2"
                        class="w-full border-2 p-2"
                        v-model="replyInput"
                    ></textarea>
                    <button
                        type="submit"
                        class="text-black-200"
                        @click="addReply"
                    >
                        Add Reply
                    </button>
                </div>

                <div class="flex justify-between mt-5" >
                    <div class="reactions flex" v-if="user">
                        <UsefulReaction @reaction="reactionHandler" :reacted="reviewData.isReactedUseful"></UsefulReaction>
                        <WinkReaction @reaction="reactionHandler" :reacted="reviewData.isReactedFunny"></WinkReaction>
                    </div>

                    <div v-if="currentUserIsOwner">
                        <form method="POST" v-if="isShowcased">
                            <button type="button" @click="removeShowcase">
                                Remove Showcase
                            </button>
                        </form>

                        <form method="POST" v-if="!isShowcased">
                            <button type="button" @click="addShowcase">
                                Showcase
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
import StarRating from "../StarRating.vue";
import UserCard from "./UserCard.vue";
import UsefulReaction from "./UsefulReaction.vue";
import WinkReaction from "./WinkReaction.vue";

export default {
    components: {
        StarRating,
        UserCard,
        UsefulReaction,
        WinkReaction
    },
    data() {
        return {
            reviewData: this.review,
            isShowcased: this.review.showcased,
            showImage: false,
            replyInput: "",
            funny: 0,
            useful: 0
        };
    },
    props: {
        review: {
            type: Object,
            required: true
        },
        currentUserIsOwner: {
            type: Boolean,
            required: true
        }
    },
    methods: {
        toggleImageView() {
            this.showImage = !this.showImage;
        },
        addReply() {
            if (!this.replyInput) {
                return; // add error
            }
            axios
                .post(`/businesses/review/${this.reviewData.id}/reply`, {
                    body: this.replyInput
                })
                .then(() => this.refreshCurrentReview());
        },
        refreshCurrentReview() {
            axios.get(`/businesses/review/${this.reviewData.id}`).then(res => {
                this.reviewData = res.data.review;
            });
        },
        addShowcase() {
            axios.post(`/reviews/${this.reviewData.id}/showcase`).then(() => {
                this.refreshCurrentReview();
                this.isShowcased = true;
            });
        },
        removeShowcase() {
            axios
                .post(`/reviews/${this.reviewData.id}/showcase/remove`)
                .then(() => {
                    this.refreshCurrentReview();
                    this.isShowcased = false;
                });
        },
        async reactionHandler(type) {
            const res = await axios.post(`/reviews/${this.reviewData.id}/react`, {type});
        }
    },
    computed: {
        imageUrl() {
            return `${window.location.origin}/storage//${this.reviewData.image.image_path}`;
        },
         user() {
            return currentUser;
    },
    }
};
</script>

<style scoped>
.review {
    padding: 25px 0;
    border-bottom: solid 1px #edf2f7;
}
</style>
