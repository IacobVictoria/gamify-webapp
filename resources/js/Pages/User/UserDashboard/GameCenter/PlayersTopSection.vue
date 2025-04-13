<template>
    <div class="flex flex-col gap-8 px-4 my-20">
        <div
            class="flex items-center justify-between px-6 py-4 rounded-xl shadow-md"
        >
            <span
                class="text-xl font-bold tracking-wide flex items-center gap-2"
            >
                🏆 Topul Jucătorilor
            </span>
        </div>

        <!-- Top 3 container -->
        <div class="flex items-end justify-center gap-4">
            <!-- Locul 3 -->
            <div class="flex flex-col items-center">
                <img
                    v-if="top10Players[2].gender === 'Male'"
                    src="/images/male.png"
                    alt="User Avatar"
                    class="w-14 h-14 rounded-full"
                />
                <img
                    v-else
                    src="/images/female.png"
                    alt="User Avatar"
                    class="w-14 h-14 rounded-full"
                />
                <p class="text-sm mt-2">
                    {{ formatName(top10Players[2].name) }}
                </p>
                <p class="text-[#613D7B]">{{ top10Players[2].score }} px</p>
                <div
                    class="min-w-[105px] min-h-[85px] py-[21.97px] bg-[#A08BB0] py-2 px-4 rounded-md"
                >
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-lg font-semibold leading-snug"
                            >Locul</span
                        >
                        <span class="text-lg font-semibold leading-snug"
                            >3</span
                        >
                    </div>
                </div>
            </div>

            <!-- Locul 1 -->
            <div class="flex flex-col items-center">
                <img
                    v-if="top10Players[0].gender === 'Male'"
                    src="/images/male.png"
                    alt="User Avatar"
                    class="w-14 h-14 rounded-full"
                />
                <img
                    v-else
                    src="/images/female.png"
                    alt="User Avatar"
                    class="w-14 h-14 rounded-full"
                />
                <p class="text-sm mt-2">
                    {{ formatName(top10Players[0].name) }}
                </p>
                <p class="text-[#FA902F]">{{ top10Players[0].score }} px</p>
                <div
                    class="flex flex-col items-center justify-center gap-5 min-w-[105px] min-h-[155px] py-[23.07px] bg-[#FA902F] rounded-md"
                >
                    <svg
                        width="29"
                        height="28"
                        viewBox="0 0 29 28"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M25.7078 7.00173H23.1328V5.28508C23.1328 5.05744 23.0424 4.83912 22.8814 4.67816C22.7204 4.51719 22.5021 4.42676 22.2745 4.42676H6.82463C6.59698 4.42676 6.37867 4.51719 6.2177 4.67816C6.05673 4.83912 5.9663 5.05744 5.9663 5.28508V7.00173H3.39133C2.93605 7.00173 2.49941 7.18259 2.17748 7.50452C1.85554 7.82646 1.67468 8.26309 1.67468 8.71838V10.435C1.67468 11.5732 2.12683 12.6648 2.93167 13.4697C3.33018 13.8682 3.80329 14.1843 4.32397 14.4C4.84465 14.6156 5.40272 14.7266 5.9663 14.7266H6.35791C6.86352 16.3289 7.82754 17.748 9.13084 18.8083C10.4341 19.8687 12.0196 20.5239 13.6912 20.6931V23.3099H11.1162C10.8886 23.3099 10.6703 23.4003 10.5093 23.5613C10.3484 23.7222 10.2579 23.9406 10.2579 24.1682C10.2579 24.3959 10.3484 24.6142 10.5093 24.7751C10.6703 24.9361 10.8886 25.0265 11.1162 25.0265H17.9828C18.2105 25.0265 18.4288 24.9361 18.5898 24.7751C18.7507 24.6142 18.8412 24.3959 18.8412 24.1682C18.8412 23.9406 18.7507 23.7222 18.5898 23.5613C18.4288 23.4003 18.2105 23.3099 17.9828 23.3099H15.4079V20.6899C18.8347 20.3433 21.6779 17.9389 22.7122 14.7266H23.1328C24.271 14.7266 25.3626 14.2745 26.1674 13.4697C26.9722 12.6648 27.4244 11.5732 27.4244 10.435V8.71838C27.4244 8.26309 27.2435 7.82646 26.9216 7.50452C26.5997 7.18259 26.163 7.00173 25.7078 7.00173ZM5.9663 13.01C5.28338 13.01 4.62842 12.7387 4.14552 12.2558C3.66262 11.7729 3.39133 11.118 3.39133 10.435V8.71838H5.9663V12.1517C5.9663 12.4378 5.98025 12.7239 6.00815 13.01H5.9663ZM25.7078 10.435C25.7078 11.118 25.4365 11.7729 24.9536 12.2558C24.4707 12.7387 23.8157 13.01 23.1328 13.01H23.0791C23.1144 12.6929 23.1323 12.3741 23.1328 12.0551V8.71838H25.7078V10.435Z"
                            fill="#FDEBE6"
                        />
                    </svg>
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-lg font-semibold leading-snug"
                            >Locul</span
                        >
                        <span class="text-lg font-semibold leading-snug"
                            >1</span
                        >
                    </div>
                </div>
            </div>

            <!-- Locul 2 -->
            <div class="flex flex-col items-center">
                <img
                    v-if="top10Players[1].gender === 'Male'"
                    src="/images/male.png"
                    alt="User Avatar"
                    class="w-14 h-14 rounded-full"
                />
                <img
                    v-else
                    src="/images/female.png"
                    alt="User Avatar"
                    class="w-14 h-14 rounded-full"
                />
                <p class="text-sm mt-2">
                    {{ formatName(top10Players[1].name) }}
                </p>
                <p class="text-[#06A77D]">{{ top10Players[1].score }} px</p>
                <div
                    class="flex items-center justify-center min-w-[105px] min-h-[120px] py-[23.07px] bg-[#6ACAB1] py-2 px-4 rounded-md"
                >
                    <div class="flex flex-col items-center justify-center pt-2">
                        <span class="text-lg font-semibold leading-snug"
                            >Locul</span
                        >
                        <span class="text-lg font-semibold leading-snug"
                            >2</span
                        >
                    </div>
                </div>
            </div>
        </div>
        <div
            v-for="player in top10Players.slice(3)"
            :key="player.id"
            class="flex items-center gap-4 bg-[#EBE0F4] py-7 px-4 rounded-xl w-12/12"
        >
            <span class="text-base font-semibold leading-tight w-2/12"
                >#{{ getPlayerPosition(player) }}</span
            >
            <span class="text-[13px] font-[500] leading-none w-10/12">{{
                player.name
            }}</span>
            <span class="text-xs font-normal leading-[14.88px] w-2/12"
                >{{ player.score }} px</span
            >
        </div>
    </div>
</template>

<script>
export default {
    name: "LeaderboardSection",

    props: {
        top10Players: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {};
    },

    methods: {
        formatName(name) {
            let formattedName = name.split(" ");
            return formattedName[0] + " " + formattedName[1].charAt(0) + ".";
        },

        getPlayerPosition(player) {
            let playerIndex = this.top10Players.findIndex(
                (p) => p.id === player.id
            );
            return playerIndex + 1;
        },
    },
};
</script>
