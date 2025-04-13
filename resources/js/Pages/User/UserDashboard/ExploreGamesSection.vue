<template>
    <div
        class="flex flex-col lg:flex-row px-4 mb-20 mt-20 items-center justify-center gap-10"
    >
        <!-- Stânga: imagine + titlu -->
        <div class="flex flex-1 flex-col gap-8 w-full max-w-[40em]">
            <div
                class="flex flex-col gap-14 bg-[#e3f1fe] rounded-[18px] p-6 sm:p-10"
            >
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <div class="flex-1 text-center lg:text-left">
                        <p
                            class="text-[24px] sm:text-[28px] font-bold leading-9 font-cocon text-[#4b81ac]"
                        >
                            Teste interactive despre nutriție
                        </p>
                        <p class="text-sm text-slate-500 leading-tight mt-2">
                            Testează-ți cunoștințele
                        </p>
                    </div>
                    <div class="flex justify-center flex-1">
                        <img
                            :src="imagePath('/user_dashboard/teste.png')"
                            alt="Teste nutriție"
                            class="max-w-full h-auto sm:h-[20em] sm:w-[20em] lg:h-[25em] lg:w-[25em]"
                        />
                    </div>
                </div>

                <!-- Buton -->
                <div
                    class="flex flex-row items-center justify-center bg-[#cdede5] rounded-[26px] w-[139px] h-[45px] gap-2 cursor-pointer mx-auto"
                >
                    <span
                        class="text-[#f45d3a] text-lg font-medium leading-[25.20px]"
                    >
                        <inertia-link
                            class="no-underline"
                            :href="route('user.dashboard.explore-games.index')"
                        >
                            Începe
                        </inertia-link>
                    </span>
                    <svg
                        width="13"
                        height="13"
                        viewBox="0 0 13 13"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M9.57304 5.58762L6.18758 2.02746C6.03146 1.85747 5.94507 1.62979 5.94702 1.39348C5.94897 1.15716 6.03911 0.931096 6.19802 0.763987C6.35693 0.596877 6.5719 0.502088 6.79662 0.500034C7.02134 0.497981 7.23784 0.588827 7.39949 0.753008L12.248 5.8517C12.3279 5.93521 12.3912 6.0345 12.4345 6.14386C12.4777 6.25321 12.5 6.37049 12.5 6.48892C12.5 6.60736 12.4777 6.72463 12.4345 6.83399C12.3912 6.94335 12.3279 7.04264 12.248 7.12615L7.39949 12.2248C7.32043 12.3109 7.22585 12.3796 7.12129 12.4268C7.01672 12.4741 6.90425 12.4989 6.79045 12.5C6.67665 12.501 6.56379 12.4782 6.45846 12.4329C6.35312 12.3876 6.25743 12.3206 6.17696 12.236C6.09648 12.1514 6.03284 12.0508 5.98975 11.94C5.94665 11.8292 5.92497 11.7105 5.92596 11.5909C5.92694 11.4712 5.95059 11.3529 5.99551 11.243C6.04043 11.133 6.10572 11.0335 6.18758 10.9504L9.57304 7.39023H1.35708C1.12977 7.39023 0.911766 7.29527 0.751033 7.12624C0.590299 6.95722 0.5 6.72797 0.5 6.48892C0.5 6.24988 0.590299 6.02063 0.751033 5.8516C0.911766 5.68258 1.12977 5.58762 1.35708 5.58762H9.57304Z"
                            fill="#F45D3A"
                        />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Dreapta: text explicativ -->
        <div
            class="flex items-center justify-center flex-1 w-full max-w-[40em] px-4"
        >
            <div class="p-4 text-center">
                <p class="text-xl font-semibold text-[#4b81ac]">
                    Îmbunătățirea Echilibrului Tău Nutrițional
                </p>
                <p class="text-base text-slate-600 mt-4">
                    Crește-ți cunoștințele despre nutriție și sănătate. Fă
                    alegeri echilibrate pentru un stil de viață sănătos.
                </p>
                <p class="text-sm text-[#999] mt-2">
                    Investind în sănătatea ta, îți crești energia și starea
                    generală de bine.
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ExploreGamesSection",
};
</script>

<style scoped>
.no-underline {
    text-decoration: none;
}
</style>
