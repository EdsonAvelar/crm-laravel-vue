<template>
    <div class="dashboard-container">
        <h2 class="text-xl font-semibold mb-4">Dashboard</h2>

        <!-- Vendas (Line Chart) -->
        <div class="chart-container">
            <apexchart type="line" height="350" :options="salesOptions" :series="salesSeries"></apexchart>
        </div>

        <!-- Vendas Porcentagem (Donut Chart) -->
        <div class="chart-container">
            <apexchart type="donut" height="350" :options="salesPercentageOptions" :series="salesPercentageSeries">
            </apexchart>
        </div>

        <!-- Agendamentos (Stacked Bar Chart) -->
        <div class="chart-container">
            <apexchart type="bar" height="350" :options="appointmentsOptions" :series="appointmentsSeries"></apexchart>
        </div>

        <!-- Funil de Conversão (Funnel Chart) -->
        <div class="chart-container">
            <apexchart type="funnel" height="350" :options="funnelOptions" :series="funnelSeries"></apexchart>
        </div>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        output: Object, // Recebe os dados do controller via Inertia
        stats: Object,  // Recebe os dados agregados do controller via Inertia
    },
    data() {
        return {
            // Configuração do gráfico de Vendas
            salesSeries: [],
            salesOptions: {
                chart: {
                    type: 'line',
                    height: 350,
                },
                title: {
                    text: 'Vendas',
                },
                xaxis: {
                    categories: [],
                },
            },
            // Configuração do gráfico de Porcentagem de Vendas (Donut)
            salesPercentageSeries: [],
            salesPercentageOptions: {
                chart: {
                    type: 'donut',
                },
                title: {
                    text: 'Vendas Porcentagem',
                },
            },
            // Configuração do gráfico de Agendamentos (Stacked Bar)
            appointmentsSeries: [],
            appointmentsOptions: {
                chart: {
                    type: 'bar',
                    stacked: true,
                },
                title: {
                    text: 'Agendamentos',
                },
                xaxis: {
                    categories: [],
                },
            },
            // Configuração do gráfico Funil de Conversão
            funnelSeries: [],
            funnelOptions: {
                chart: {
                    type: 'funnel',
                },
                title: {
                    text: 'Funil de Conversão',
                },
            },
        };
    },
    mounted() {
        // Atualiza os gráficos com os dados recebidos via props
        this.updateCharts();
    },
    methods: {
        updateCharts() {
            // Atualiza o gráfico de vendas (Line chart)
            this.salesSeries = [
                {
                    name: 'Vendas',
                    data: this.output.vendas,
                },
            ];
            this.salesOptions.xaxis.categories = this.output.vendedores;

            // Atualiza o gráfico de Porcentagem de Vendas (Donut chart)
            this.salesPercentageSeries = this.output.vendas;

            // Atualiza o gráfico de Agendamentos (Stacked bar chart)
            this.appointmentsSeries = [
                {
                    name: 'Realizados',
                    data: this.output.agendamentos_realizado,
                },
                {
                    name: 'Faltas',
                    data: this.output.agendamentos_faltou,
                },
            ];
            this.appointmentsOptions.xaxis.categories = this.output.vendedores;

            // Atualiza o gráfico Funil de Conversão
            this.funnelSeries = [
                {
                    name: 'Funil',
                    data: this.stats.funil,
                },
            ];
        },
    },
};
</script>

<style scoped>
.dashboard-container {
    padding: 20px;
}

.chart-container {
    margin-bottom: 40px;
}
</style>
