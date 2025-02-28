<template>
    <div class="flex flex-row items-center justify-between px-10 mt-10">
        <div v-for="stat in stats">
            <div class="flex flex-col">
                <div class="flex flex-row gap-1">
                    <img :src="imagePath(stat.image)" class="w-12" />
                    <div class="flex flex-col">
                        <span class="text-base leading-[17.39px] tracking-tight">{{ stat.name }}</span>
                        <span class="font-semibold">{{ stat.value }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'UserStatsSection',
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            stats: [
                {
                    name: 'Puncte',
                    value: this.user.score,
                    image: '/user_dashboard/points.png'
                },
                {
                    name: 'Loc',
                    value: this.user.position_leaderboard,
                    image: '/user_dashboard/podium.png'
                },
                {
                    name: 'Insigne',
                    value: this.user.nr_badges,
                    image: '/user_dashboard/small_badge.png'
                }
            ]
        }
    }
}

</script>