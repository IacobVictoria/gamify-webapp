<template>

  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="max-w-[90%] mx-auto sm:px-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex flex-row gap-5 px-4 m-12">
            <div
              class="bg-[#fdebe6] rounded-full p-2 w-24 h-24 justify-center items-center inline-flex overflow-hidden">
              <img :src="imagePath('profile_woman1.png')" class="object-cover w-full h-full" />
            </div>
            <div>
              <span class="text-xl font-normal leading-snug tracking-tight">Salut, </span>
              <span class="text-xl font-semibold leading-snug tracking-tight">{{ this.$page.props.user.name }}!</span>
              <div class="flex flex-col max-w-[241px]">
                <h2 class="max-w-full text-sm tracking-normal leading-loose text-[#FA902F] capitalize rounded-none">
                  Bonding advocate
                </h2>
                <!-- in functie de cate metalii are o sa fie un alt nume acolo -->
                <div class="flex flex-col items-start mt-1.5 w-full bg-rose-100 rounded min-h-[8px]" role="progressbar"
                  aria-valuenow="29" aria-valuemin="0" aria-valuemax="500">
                  <div :class="progressClass" class="rounded min-h-[8px]" :style="{ width: progressBarWidth + '%' }">
                  </div>
                </div>
                <div class="flex justify-between mt-2">
                  <span class="text-sm text-[#FFD700]">Bronze</span>
                  <span class="text-sm text-[#C0C0C0]">Argint</span>
                  <span class="text-sm text-[#FF4500]">Aur</span>
                </div>

              </div>
            </div>
          </div>
          <UserStatsSection></UserStatsSection>
          <ExploreGamesSection></ExploreGamesSection>
          <GameCenterSection></GameCenterSection>
          <ShoppingCenterSection></ShoppingCenterSection>
          <WellnessCenterSection></WellnessCenterSection>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserStatsSection from './UserDashboard/UserStatsSection.vue';
import { Head } from '@inertiajs/vue3';
import GameCenterSection from './UserDashboard/GameCenterSection.vue';
import ExploreGamesSection from './UserDashboard/ExploreGamesSection.vue';
import ShoppingCenterSection from './UserDashboard/ShoppingCenterSection.vue';
import WellnessCenterSection from './UserDashboard/WellnessCenterSection.vue'

export default {
  components: {
    AuthenticatedLayout,
    UserStatsSection,
    Head,
    GameCenterSection,
    ExploreGamesSection,
    ShoppingCenterSection,
    WellnessCenterSection
  },
  data() {
    return {
      // Exemplu: progresul utilizatorului
      userProgress: 150, // Poate fi orice valoare între 0 și 500
    };
  },
  computed: {
    progressBarWidth() {
      return (this.userProgress / 500) * 100;
    },
    progressClass() {
      if (this.userProgress <= 100) {
        return 'bg-yellow-300'; // Bronze
      } else if (this.userProgress <= 300) {
        return 'bg-orange-400'; // Argint
      } else {
        return 'bg-red-500'; // Aur
      }
    }
  }
}
</script>
