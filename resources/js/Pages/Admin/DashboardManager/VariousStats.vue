<template>
    <div class="bg-white p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-5">
            📊 Statistici lunare
        </h2>

        <!-- Clasament -->
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                🏆 Top 5 utilizatori activi
            </h3>
            <div class="flex justify-around gap-4">
                <div
                    v-for="(user, index) in variousStats.topUsers"
                    :key="user.id"
                    class="flex flex-col items-center rounded-xl shadow-md p-3 w-24"
                    :class="{
                        'border-2 border-yellow-500 scale-110': index === 0,
                    }"
                >
                    <p
                        :class="getRankingColor(index)"
                        class="text-lg font-bold"
                    >
                        #{{ index + 1 }}
                    </p>
                    <img
                        :src="getAvatar(user)"
                        alt="Avatar utilizator"
                        class="w-14 h-14 rounded-full mt-2 mb-1"
                    />
                    <p class="text-xs font-semibold text-gray-800 text-center">
                        {{ user.name }}
                    </p>
                    <p class="text-xs text-gray-500">{{ user.score }} puncte</p>
                </div>
            </div>
        </div>

        <!-- Scanări QR -->
        <div
            class="mb-6 flex items-center bg-gray-150 p-4 rounded-lg shadow-xl"
        >
            <img
                :src="imagePath('qr-code.png')"
                alt="Iconiță QR"
                class="w-16 h-16 mr-3"
            />
            <div>
                <p class="text-lg font-bold text-gray-800">
                    {{ variousStats.weeklyQrScans }}
                </p>
                <p class="text-sm text-gray-500">
                    Scanări QR în această lună
                </p>
            </div>
        </div>

        <!-- Produse cele mai adăugate la favorite -->
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                💖 Produse cel mai des adăugate la favorite
            </h3>
            <div class="flex space-x-4 overflow-x-auto scrollbar-hide">
                <div
                    v-for="(product, index) in variousStats.topWishlistProducts"
                    :key="index"
                    class="min-w-[180px] border border-gray-200 rounded-md bg-white p-3 flex flex-col items-center"
                >
                    <img
                        :src="product.image_url || defaultImage"
                        alt="Imagine produs"
                        class="w-32 h-32 object-cover rounded-md mb-2"
                    />
                    <h3 class="text-sm font-medium text-gray-800 text-center">
                        {{ product.name }}
                    </h3>
                    <p class="text-xs text-gray-500">
                         ❤️ {{ product.wishlists_count }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Rata de conversie -->
        <div class="text-center bg-green-100 p-4 rounded-lg shadow">
            <p class="text-lg font-bold text-green-700">
                {{ variousStats.conversionRate }}%
            </p>
            <p class="text-sm text-gray-500 font-semibold">
                Rată de conversie a utilizatorilor
            </p>
            <p class="text-xs text-gray-600 mt-1">
                Din 100 de utilizatori care și-au creat cont luna aceasta,
                {{ variousStats.conversionRate }}% au plasat o comandă.
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        variousStats: Object,
    },
    methods: {
        getAvatar(user) {
            return user.gender === "Male"
                ? "/images/male.png"
                : "/images/female.png";
        },
        getRankingColor(index) {
            return index === 0
                ? "text-yellow-500"
                : index === 1
                ? "text-gray-600"
                : "text-brown-500";
        },
    },
};
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
