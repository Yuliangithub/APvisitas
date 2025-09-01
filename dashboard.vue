<template>
  <div class="p-6">
    <!-- Tarjetas resumen -->
    <div class="grid grid-cols-4 gap-4 mb-6">
      <div class="card">
        <h3>Total Visitas</h3>
        <p>{{ dashboard.visitas.total }}</p>
      </div>
      <div class="card">
        <h3>Pendientes</h3>
        <p>{{ dashboard.visitas.pendientes }}</p>
      </div>
      <div class="card">
        <h3>Finalizadas</h3>
        <p>{{ dashboard.visitas.finalizadas }}</p>
      </div>
      <div class="card">
        <h3>Hallazgos Pendientes</h3>
        <p>{{ dashboard.hallazgos.pendientes }}</p>
      </div>
    </div>

    <!-- Gráfica hallazgos por estado -->
    <div class="mb-6">
      <canvas id="chartHallazgos"></canvas>
    </div>

    <!-- Tabla próximos a vencer -->
    <div class="card p-4">
      <h3 class="text-lg mb-2">Hallazgos próximos a vencer</h3>
      <table class="table-auto w-full border">
        <thead>
          <tr>
            <th class="border p-2">ID</th>
            <th class="border p-2">Descripción</th>
            <th class="border p-2">Responsable</th>
            <th class="border p-2">Fecha Mejora</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="a in dashboard.alertas" :key="a.id">
            <td class="border p-2">{{ a.id }}</td>
            <td class="border p-2">{{ a.descripcion }}</td>
            <td class="border p-2">{{ a.responsable }}</td>
            <td class="border p-2">{{ a.fecha_mejora }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
import Chart from "chart.js/auto"

const dashboard = ref({
  visitas: { total: 0, pendientes: 0, finalizadas: 0 },
  hallazgos: { pendientes: 0, en_proceso: 0, cumplidos: 0, vencidos: 0 },
  alertas: []
})

onMounted(async () => {
  const { data } = await axios.get("http://192.168.1.216/APvisitas/api/dashboard.php")
  dashboard.value = data

  // Crear gráfica de hallazgos por estado
  const ctx = document.getElementById("chartHallazgos")
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["Pendientes", "En Proceso", "Cumplidos", "Vencidos"],
      datasets: [{
        label: "Hallazgos",
        data: [
          data.hallazgos.pendientes,
          data.hallazgos.en_proceso,
          data.hallazgos.cumplidos,
          data.hallazgos.vencidos
        ]
      }]
    }
  })
})
</script>

<style>
.card {
  background: #f8f9fa;
  padding: 16px;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
</style>
