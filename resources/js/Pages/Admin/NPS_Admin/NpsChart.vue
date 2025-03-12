<!-- LINE CHART -->
<!-- <template>
    <Chart :size="{ width: 500, height: 400 }" :data="data" :margin="margin" :direction="direction">
        <template #layers>
            <Grid strokeDasharray="2,2" />
      
            <Line :dataKeys="['month', 'nps']" />
        </template>
    </Chart>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { Chart, Grid, Line } from 'vue3-charts';

export default defineComponent({
    name: 'NpsChart',
    components: { Chart, Grid, Line },
    props: {
        monthlyNpsData: {
            type: Array,
            required: true,
            default: () => [],
        },
    },
    setup(props) {
        const data = ref(
            props.monthlyNpsData.map((item) => ({
                month: item.month,
                nps: item.nps,
            }))
        );

        const direction = ref('horizontal'); 
        const margin = ref({
            left: 50,
            top: 20,
            right: 20,
            bottom: 50,
        });

        return { data, direction, margin };
    },
});
</script> -->

<!-- BAR CHART -->
<template>
    <Chart :size="{ width: 500, height: 420 }" :data="data" :margin="margin" :direction="direction" :axis="axis">

        <template #layers>
            <Grid strokeDasharray="2,2" />
            <!-- Bar pentru NPS -->
            <Bar :dataKeys="['month', 'nps', 'responses', 'entries']" :barStyle="{ fill: '#90e0ef' }" />
        </template>

        <template #widgets>
            <Tooltip borderColor="#48CAE4" :config="{
                nps: { color: '#90e0ef' },
                responses: { color: '#48cae4' },
                entries: { color: '#0096c7' }
            }" />
        </template>
    </Chart>
    <!-- Legendă pentru explicații -->
    <div class="legend">
        <ul>
            <li><span class="color-box" style="background-color: #90e0ef;"></span> <strong>NPS:</strong> Net Promoter
                Score
            </li>
            <li><span class="color-box" style="background-color: #48cae4;"></span> <strong>Responses:</strong> Total
                feedback responses</li>
            <li><span class="color-box" style="background-color: #0096c7;"></span> <strong>Entries:</strong> Total
                survey
                entries</li>
        </ul>
    </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { Chart, Grid, Bar, Tooltip } from 'vue3-charts';

export default defineComponent({
    name: 'NpsBarChart',
    components: { Chart, Grid, Bar, Tooltip },
    props: {
        monthlyNpsData: {
            type: Array,
            required: true,
            default: () => [],
        },
    },
    setup(props) {
        const data = ref(
            props.monthlyNpsData.map((item) => ({
                month: item.month, // Luna
                nps: item.nps, // Scorul NPS
                responses: item.totalResponses,
                entries: item.entries
            }))
        );

        const direction = ref('horizontal');
        const margin = ref({
            left: 50,
            top: 20,
            right: 20,
            bottom: 50,
        });

        const axis = ref({
            primary: {
                type: 'band', // Axa X
            },
            secondary: {
                domain: ['dataMin', 'dataMax + 10'], // Axa Y
                type: 'linear',
                ticks: 5,
            },
        });

        return { data, direction, margin, axis };
    },
});
</script>
<style>
.legend {
    margin-top: 20px;
    margin-left: 40px;
}

.legend ul {
    list-style: none;
    padding: 0;
}

.legend li {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.legend .color-box {
    width: 16px;
    height: 16px;
    display: inline-block;
    margin-right: 8px;
    border-radius: 4px;
}
</style>
