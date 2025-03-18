<template>

  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-white leading-tight text-center bg-gradient-to-r from-purple-500 to-pink-500 p-4 rounded-lg shadow-lg">
        🎮 Gaming Dashboard - Level Up! 🚀
      </h2>
    </template>


    <div class="py-12">
      <div class="max-w-[90%] mx-auto sm:px-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex flex-row gap-5 px-4 m-12">
            <div class="rounded-full p-2 w-24 h-24 justify-center items-center inline-flex overflow-hidden">

              <img v-if="account.gender === 'Male'" src="/images/male.png" alt="User Avatar"
                class="w-20 h-20 rounded-full shadow-md cursor-pointer" />
              <img v-else src="/images/female.png" alt="User Avatar"
                class="w-20 h-20 rounded-full shadow-md cursor-pointer" />

            </div>
            <div>
              <span class="text-xl font-normal leading-snug tracking-tight">Salut, </span>
              <span class="text-xl font-semibold leading-snug tracking-tight">{{ account.name }}!</span>
              <div class="flex flex-col max-w-[241px] mt-2">
                <h2 class="max-w-full text-sm font-semibold text-[#FA902F] capitalize">
                  {{ userRank }}
                </h2>

                <!-- Progress Bar -->
                <div class="relative w-full h-3 bg-gray-200 rounded-full overflow-hidden mt-1.5">
                  <div :class="progressClass"
                    class="absolute top-0 left-0 h-full rounded-full transition-all duration-500 ease-in-out"
                    :style="{ width: progressBarWidth + '%' }">
                  </div>
                </div>

                <!-- Labels -->
                <div class="flex justify-between mt-2 text-xs font-medium">
                  <span class="text-[#CD7F32]">Bronze</span>
                  <span class="text-[#C0C0C0]">Argint</span>
                  <span class="text-[#FFD700]">Aur</span>
                </div>
              </div>
            </div>
          </div>
          <UserStatsSection :user="account"></UserStatsSection>
          <ScanQrCodeProducts></ScanQrCodeProducts>
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
import WellnessCenterSection from './UserDashboard/WellnessCenterSection.vue';
import ScanQrCodeProducts from './UserDashboard/ScanQrCodeProducts.vue';

export default {
  components: {
    AuthenticatedLayout,
    UserStatsSection,
    Head,
    GameCenterSection,
    ExploreGamesSection,
    ShoppingCenterSection,
    WellnessCenterSection,
    ScanQrCodeProducts
  },
  props: {
    account: {
      type: Object,
      required: true
    }
  },
  computed: {
    progressBarWidth() {
      return Math.min((this.account.score / 500) * 100, 100); // Maxim 100%
    },
    progressClass() {
      if (this.account.score < 100) {
        return 'bg-[#CD7F32]'; // Bronze
      } else if (this.account.score < 300) {
        return 'bg-[#C0C0C0]'; // Argint
      } else {
        return 'bg-[#FFD700]'; // Aur
      }
    },
    userRank() {
      if (this.account.score >= 50 && this.account.score < 100) {
        return "Bronze Player";
      } else if (this.account.score >= 100 && this.account.score < 300) {
        return "Silver Player";
      } else if (this.account.score > 300) {
        return "Golden Champion";
      }
      else {
        return "New Player";
      }
    }
  }
}
</script>
