<script setup>
import ApexChart from 'vue3-apexcharts';

// Configurações dos gráficos fake
const lineChartOptions = {
    chart: {
        type: 'line',
        height: 350
    },
    series: [{
        name: 'Total Revenue',
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
    }, {
        name: 'Total Sales',
        data: [20, 30, 45, 50, 49, 60, 70, 110, 150]
    }],
    xaxis: {
        categories: ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
    }
};

const barChartOptions = {
    chart: {
        type: 'bar',
        height: 350
    },
    series: [{
        name: 'Revenue',
        data: [10, 20, 30, 40, 50, 60, 70]
    }, {
        name: 'Sales',
        data: [15, 25, 35, 45, 55, 65, 75]
    }],
    xaxis: {
        categories: ['M', 'T', 'W', 'T', 'F', 'S', 'S']
    }
};

const donutChartOptions = {
    chart: {
        type: 'donut',
    },
    series: [65, 34, 45, 12],
    labels: ['Desktop', 'Tablet', 'Mobile', 'Unknown'],
};
</script>

<template>

    <Head :title="` | ${$page.component}`" />
    <div class="min-h-screen bg-gray-100 p-4">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Cards Resumo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Views</p>
                            <p class="text-2xl font-bold">$3.456K</p>
                        </div>
                        <i class="fas fa-eye text-3xl text-blue-500"></i>
                    </div>
                    <p class="text-green-500 text-sm mt-2">0.43% ↑</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Profit</p>
                            <p class="text-2xl font-bold">$45.2K</p>
                        </div>
                        <i class="fas fa-shopping-cart text-3xl text-blue-500"></i>
                    </div>
                    <p class="text-green-500 text-sm mt-2">4.35% ↑</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Product</p>
                            <p class="text-2xl font-bold">2.450</p>
                        </div>
                        <i class="fas fa-box text-3xl text-blue-500"></i>
                    </div>
                    <p class="text-green-500 text-sm mt-2">2.59% ↑</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Users</p>
                            <p class="text-2xl font-bold">3.456</p>
                        </div>
                        <i class="fas fa-users text-3xl text-blue-500"></i>
                    </div>
                    <p class="text-red-500 text-sm mt-2">0.95% ↓</p>
                </div>
            </div>

            <!-- Gráfico de Linhas -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-bold mb-4">Total Revenue vs Total Sales</h2>
                <ApexChart type="line" :options="lineChartOptions" :series="lineChartOptions.series" height="350">
                </ApexChart>
            </div>

            <!-- Gráfico de Barras e Donut -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-lg font-bold mb-4">Profit this Week</h2>
                    <ApexChart type="bar" :options="barChartOptions" :series="barChartOptions.series" height="350">
                    </ApexChart>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-lg font-bold mb-4">Visitors Analytics</h2>
                    <ApexChart type="donut" :options="donutChartOptions" :series="donutChartOptions.series"></ApexChart>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Adicione estilos adicionais se necessário */
</style>
