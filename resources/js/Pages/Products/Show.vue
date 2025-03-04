<template>
    <Layout>
        <main class="mt-32 mb-20">
            <div class="bg-white">
                <div class="mx-auto max-w-7xl overflow-hidden sm:px-6 lg:px-8">
                    <div class="mb-4">
                        <inertia-link :href="route('products.index')"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Go Back
                        </inertia-link>
                    </div>
                    <!-- Product -->
                    <div class="lg:grid lg:grid-cols-7 lg:grid-rows-1 lg:gap-x-8 lg:gap-y-10 xl:gap-x-16">
                        <!-- Product image -->
                        <div class="lg:col-span-4 lg:row-end-1">
                            <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                                <img src="/images/pic1.jpg" :alt="product.imageAlt"
                                    class="object-cover object-center" />
                            </div>
                        </div>

                        <!-- Product details -->
                        <div
                            class="mx-auto mt-14 max-w-2xl sm:mt-16 lg:col-span-3 lg:row-span-2 lg:row-end-2 lg:mt-0 lg:max-w-none">
                            <div class="flex flex-col-reverse">
                                <div class="mt-4">
                                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{
                                        product.name }}</h1>
                                         <h2 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Category: {{
                                        product.category }}</h2>
                                    <button @click="isFavorite ? dislikeProduct(product) : likeProduct(product)">
                                        <AddHeartSVG :svg-class="isFavorite ? 'text-red-400' : 'text-gray-400'">
                                        </AddHeartSVG>
                                    </button>
                                    <h2 id="information-heading" class="sr-only">Product information</h2>

                                </div>
                            </div>

                            <p class="mt-6 text-gray-500">{{ product.description }}</p>
                            <div class="mt-6 text-lg font-medium">
                                <span v-if="product.old_price" class="text-red-500 line-through mr-2">
                                    {{ product.old_price }} RON
                                </span>
                                <span :class="{ 'text-green-600': product.old_price }">
                                    {{ product.price }} RON
                                </span>
                            </div>

                            <!---Comparison  -->
                            <ComparisonDropdown :product="product" :isChecked="comparisonChecked"></ComparisonDropdown>

                            <div class="mt-6">
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <select id="quantity" v-model="quantity"
                                    class="mt-1 block w-16 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" disabled>Select quantity</option>
                                    <option v-for="num in 10" :key="num" :value="num">{{ num }}</option>
                                </select>
                            </div>


                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                                <template v-if="isLoggedIn()">
                                    <button @click="addToCart(product)" v-if="authUserHasRole('User')"
                                        class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">
                                        Add to cart for {{ product.price }}
                                    </button>
                                </template>
                                <button type="button"
                                    class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-50 px-8 py-3 text-base font-medium text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">Preview</button>
                            </div>

                            <div class="mt-10 border-t border-gray-200 pt-10">
                                <h3 class="text-sm font-medium text-gray-900">Highlights</h3>
                                <div class="prose prose-sm mt-4 text-gray-500">
                                    <ul role="list">
                                        <li v-for="highlight in product.highlights" :key="highlight">{{ highlight }}
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-10 border-t border-gray-200 pt-10">
                                <h3 class="text-sm font-medium text-gray-900">License</h3>
                                <p class="mt-4 text-sm text-gray-500">{{ license.summary }} <a :href="license.href"
                                        class="font-medium text-indigo-600 hover:text-indigo-500">Read full license</a>
                                </p>
                            </div>

                            <div class="mt-10 border-t border-gray-200 pt-10">
                                <h3 class="text-sm font-medium text-gray-900">Share</h3>
                                <ul role="list" class="mt-4 flex items-center space-x-6">
                                    <li>
                                        <a href="#"
                                            class="flex h-6 w-6 items-center justify-center text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Share on Facebook</span>
                                            <svg class="h-5 w-5" aria-hidden="true" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="flex h-6 w-6 items-center justify-center text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Share on Instagram</span>
                                            <svg class="h-6 w-6" aria-hidden="true" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="flex h-6 w-6 items-center justify-center text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Share on X</span>
                                            <svg class="h-5 w-5" aria-hidden="true" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M11.4678 8.77491L17.2961 2H15.915L10.8543 7.88256L6.81232 2H2.15039L8.26263 10.8955L2.15039 18H3.53159L8.87581 11.7878L13.1444 18H17.8063L11.4675 8.77491H11.4678ZM9.57608 10.9738L8.95678 10.0881L4.02925 3.03974H6.15068L10.1273 8.72795L10.7466 9.61374L15.9156 17.0075H13.7942L9.57608 10.9742V10.9738Z" />
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div v-if="noStatistics">
                        <ReviewSummary :averageRating="averageRating" :statistics="statistics"></ReviewSummary>
                    </div>
                    <div v-if="reviews.length > 0" class="flex gap-16">
                        <SortingComponent :filter="filter" v-model="sortOrder" :options="sortOptions">
                        </SortingComponent>
                        <div class="flex items-start">
                            <button @click="authorizedBuyers"
                                class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <VerifiedSVG class="mr-2" />
                                Doar cumpărători
                            </button>
                        </div>

                    </div>
                    <ProductsReviewList :reviews="reviews" :productId="product.id" :message="noBuyersMessage"
                        :statistics="statistics" :averageRating="averageRating">
                    </ProductsReviewList>
                    <TabGroup as="div">
                        <TabPanels as="template">
                            <TabPanel class="text-sm text-gray-500  mt-32">
                                <h3>------------------------------------------------------</h3>
                                <h3>Frequently Asked Questions</h3>
                                <dl>
                                    <template v-for="faq in faqs" :key="faq.question">
                                        <dt class="mt-10 font-medium text-gray-900">{{ faq.question }}</dt>
                                        <dd class="prose prose-sm mt-2 max-w-none text-gray-500">
                                            <p>{{ faq.answer }}</p>
                                        </dd>
                                    </template>
                                </dl>
                            </TabPanel>

                            <TabPanel class="pt-10">
                                <h3 class="sr-only">License</h3>
                                <div class="prose prose-sm max-w-none text-gray-500" v-html="license.content" />
                            </TabPanel>
                        </TabPanels>
                    </TabGroup>
                </div>
            </div>
        </main>
    </Layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { TabGroup, TabPanel, TabPanels } from '@headlessui/vue';
import ReviewForm from '../Reviews/ReviewForm.vue';
import StarRating from 'vue-star-rating';
import ProductsReviewList from '../Reviews/ProductsReviewList.vue';
import SortingComponent from '@/Components/SortingComponent.vue';
import debounce from "lodash/fp/debounce";
import VerifiedSVG from '@/Components/VerifiedSVG.vue';
import ReviewSummary from '../Reviews/ReviewSummary.vue';
import AddHeartSVG from '@/Components/AddHeartSVG.vue';
import ComparisonDropdown from './ComparisonDropdown.vue';


export default {
    components: {
        Layout,
        TabGroup,
        TabPanel,
        TabPanels,
        ReviewForm,
        StarRating,
        ProductsReviewList,
        SortingComponent,
        VerifiedSVG,
        ReviewSummary,
        AddHeartSVG,
        ComparisonDropdown
    },
    props: {
        product: Object,
        reviews: Array,
        noBuyersMessage: String,
        statistics: Map,
        averageRating: Number,
        noStatistics: Boolean,
        isFavorite: Boolean,
        comparisonChecked: Boolean
    },

    data() {
        return {
            showReviewForm: false,
            editMode: false,
            editReviewForm: false,
            quantity: 1,
            sortOrder: '',
            sortOptions: [
                { value: 'noi', label: 'Cele mai noi' },
                { value: 'populare', label: 'Cele mai populare' },
            ],
            filter: { id: 'sortOrder', name: 'sortOrder', type: 'sorting', placeholder: 'Selectează metoda de sortare' },
            faqs: [
                {
                    question: 'What format are these icons?',
                    answer: 'The icons are in SVG (Scalable Vector Graphic) format. They can be imported into your design tool of choice and used directly in code.'
                },
                {
                    question: 'Can I use the icons at different sizes?',
                    answer: "Yes. The icons are drawn on a 24 x 24 pixel grid, but the icons can be scaled to different sizes as needed. We don't recommend going smaller than 20 x 20 or larger than 64 x 64 to retain legibility and visual balance."
                }
            ],
            license: {
                href: '#',
                summary: 'For personal and professional use. You cannot resell or redistribute these icons in their original or modified state.',
                content: `
                    <h4>Overview</h4>
                    <p>For personal and professional use. You cannot resell or redistribute these icons in their original or modified state.</p>
                    <ul role="list">
                        <li>You're allowed to use the icons in unlimited projects.</li>
                        <li>Attribution is not required to use the icons.</li>
                    </ul>
                    <h4>What you can do with it</h4>
                    <ul role="list">
                        <li>Use them freely in your personal and professional work.</li>
                        <li>Make them your own. Change the colors to suit your project or brand.</li>
                    </ul>
                    <h4>What you can't do with it</h4>
                    <ul role="list">
                        <li>Don't be greedy. Selling or distributing these icons in their original or modified state is prohibited.</li>
                        <li>Don't be evil. These icons cannot be used on websites or applications that promote illegal or immoral beliefs or activities.</li>
                    </ul>`
            }
        };
    },
    methods: {

        addToCart(product) {
            this.$inertia.post(route('user.shopping-cart.add'), {
                product: product,
                quantity: this.quantity
            });
        },
        authorizedBuyers() {
            this.$inertia.get(route('products.show', this.product.id), {
                buyers: true,
            }, {
                preserveState: true,
                replace: true,
            });
        },
        async likeProduct(product) {
            await this.$inertia.post(route('wishlist.products.like', product.id), {}, {
                onSuccess: (page) => {

                }
            });
        },

        async dislikeProduct(product) {
            await this.$inertia.post(route('wishlist.products.dislike', product.id), {}, {
                onSuccess: (page) => {

                }
            });
        }
    },
    watch: {
        sortOrder: {
            handler: debounce(300, function () {
                this.$inertia.get(route('products.show', this.product.id), {

                    order: this.sortOrder,
                }, {
                    preserveState: true,
                    replace: true,
                });
            }),
            deep: true,
        },
    },
};
</script>