<template>
    <div class="flex flex-col gap-8 px-4 my-20">
        <div class="flex flex-row items-center justify-between">
            <span class="text-lg font-medium leading-none text-[#7B7F7D]">Game Center</span>
            <inertia-link>
                <div class="flex flex-row items-center gap-2">
                    <inertia-link class="text-[#999999] text-sm font-light leading-none"
                        :href="route('user.dashboard.game_center.index')">View more</inertia-link>
                    <svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7 5.875L7.28326 6.28702C7.41313 6.19774 7.4933 6.05247 7.4996 5.895C7.5059 5.73754 7.4376 5.58632 7.3153 5.48694L7 5.875ZM0.716736 9.58798C0.489183 9.74442 0.431536 10.0557 0.587979 10.2833C0.744422 10.5108 1.05571 10.5685 1.28326 10.412L0.716736 9.58798ZM0.684704 1.38806L6.6847 6.26306L7.3153 5.48694L1.3153 0.611943L0.684704 1.38806ZM6.71674 5.46298L0.716736 9.58798L1.28326 10.412L7.28326 6.28702L6.71674 5.46298Z"
                            fill="#979797" />
                    </svg>
                </div>
            </inertia-link>
        </div>
        <div class="grid grid-cols-2 gap-8 pl-32 pr-32">
            <div v-for="option in gameCenter" class="flex flex-row justify-between w-full shadow-lg rounded-xl h-60"
                :class="option.bgColor">
                <div class="flex overflow-hidden  flex-col w-full rounded-xl items-center">
                    <inertia-link class="no-underline " :href="route(option.route)">
                        <div class="flex flex-row items-center justify-between px-4 min-h-[80px]">
                            <span class="font-semibold leading-tight" :class="option.textColor">
                                {{ option.name }}
                            </span>
                        </div>
                    </inertia-link>

                    <div>
                        <img :src="imagePath(option.image)" class="w-32" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'GameCenterSection',

    data() {
        return {
            gameCenter: [
                {
                    name: 'Insignele mele',
                    image: '/user_dashboard/badges.png',
                    bgColor: 'bg-[#FEE9D5]',
                    textColor: 'text-[#F45D3A]',
                    route: 'user.calendar.index'
                },
                {
                    name: 'Topul jucătorilor',
                    image: '/user_dashboard/top_players.png',
                    bgColor: 'bg-[#CDEDE5]',
                    textColor: 'text-[#06A77D]',
                    route: 'user.calendar.index'
                },
                {
                    name: 'Medaliile mele',
                    image: '/user_dashboard/medals_banner.png',
                    bgColor: 'bg-[#FAD0D2]',  // roz pastel
                    textColor: 'text-[#F28D8C]',
                    route: 'user.calendar.index'
                },
                {
                    name: 'Evenimentele mele',
                    image: '/user_dashboard/events_banner.png',
                    bgColor: 'bg-[#D1D3D4]',  // gri pastel
                    textColor: 'text-[#7B7F7D]',
                    route: 'user.calendar.index'
                }
            ]
        }
    }
}

</script>
<style scoped>
.no-underline {
    text-decoration: none;
}
</style>