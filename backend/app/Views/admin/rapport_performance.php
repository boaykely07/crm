<?php
// app/Views/admin/rapport_performance.php
?>
<div class="container-fluid mt-4">
    <h2 class="mb-4 text-dark fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i>Rapport Performance</h2>

    <!-- Temps moyen de résolution -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                <div class="card-body p-4 bg-gradient-primary text-white">
                    <h5 class="card-title mb-3"><i class="fas fa-clock me-2"></i>Temps moyen de résolution</h5>
                    <p class="card-text display-5 fw-bold"><?= $avg_resolution_time ?> heures</p>
                    <small class="text-light">Basé sur les tickets fermés</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Satisfaction moyenne par agent -->
    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white rounded-top-3">
            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Satisfaction moyenne par agent</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 rounded-start">Agent</th>
                            <th class="px-4 py-3 rounded-end">Satisfaction moyenne (étoiles)</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: rgb(26, 8, 166); color: #fff;">
                        <?php if (!empty($satisfaction_by_agent)): ?>
                            <?php foreach ($satisfaction_by_agent as $agent): ?>
                                <tr class="table-row-hover">
                                    <td class="px-4 py-3"><?= esc($agent['nom'] . ' ' . $agent['prenom']) ?></td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2"><?= round($agent['avg_satisfaction'], 2) ?> / 5</span>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-star fa-3x mb-3 text-warning"></i>
                                        <h5>Aucune donnée de satisfaction disponible</h5>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Nombre de tickets ouverts/fermés par semaine -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white rounded-top-3">
            <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Tickets par semaine (dernières 4 semaines)</h5>
        </div>
        <div class="card-body p-4">
            <canvas id="ticketsChart" height="120"></canvas>
        </div>
    </div>
</div>

<!-- Styles personnalisés -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, rgb(26, 8, 166) 0%, rgb(40, 20, 180) 100%);
    }
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
    .table-row-hover:hover {
        background-color: rgb(40, 20, 180) !important;
        transition: background-color 0.2s ease;
    }
    .table th, .table td {
        border: none;
    }
    .table thead th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .display-5 {
        font-size: 2.5rem;
        line-height: 1.2;
    }
    @media (max-width: 576px) {
        .display-5 {
            font-size: 1.8rem;
        }
        .card-body {
            padding: 1.5rem !important;
        }
        canvas#ticketsChart {
            height: 200px !important;
        }
    }
</style>

<!-- Inclure Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ticketsChart').getContext('2d');
        const ticketsByWeek = <?= json_encode($tickets_by_week) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ticketsByWeek.map(week => week.week_label),
                datasets: [
                    {
                        label: 'Tickets Ouverts',
                        data: ticketsByWeek.map(week => week.ouverts),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(54, 162, 235, 0.9)'
                    },
                    {
                        label: 'Tickets Fermés',
                        data: ticketsByWeek.map(week => week.fermes),
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(40, 167, 69, 0.9)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre de tickets',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Semaine',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 10,
                        cornerRadius: 4
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
</script>