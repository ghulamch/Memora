@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="admin-container" x-data="dashboardApp()">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-chart-line"></i> Dashboard Admin
            </h1>
            <p class="page-subtitle">Overview statistik dan aktivitas memora</p>
        </div>
        <div class="header-actions">
            <span class="last-updated">
                <i class="fas fa-clock"></i> 
                Update terakhir: <span x-text="lastUpdated"></span>
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Total Photos -->
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-images"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Total Foto</p>
                <h2 class="stat-value" x-text="formatNumber(stats.totalPhotos)">0</h2>
            </div>
        </div>


        <!-- Total Sessions -->
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-tag"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Total Sesi</p>
                <h2 class="stat-value" x-text="formatNumber(stats.totalSessions)">0</h2>
                <p class="stat-detail">
                    <span x-text="stats.activeSessions"></span> sesi aktif hari ini
                </p>
            </div>
        </div>

        <!-- Templates -->
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Template Tersedia</p>
                <h2 class="stat-value" x-text="formatNumber(stats.totalTemplates)">0</h2>
                <p class="stat-detail">
                    <span x-text="stats.activeTemplates"></span> template aktif
                </p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <!-- Photo Upload Trend -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-area"></i> Trend Upload Foto
                </h3>
                <div class="chart-actions">
                    <button 
                        @click="changePeriod('7days')" 
                        class="period-btn"
                        :class="period === '7days' ? 'active' : ''"
                    >7 Hari</button>
                    <button 
                        @click="changePeriod('30days')" 
                        class="period-btn"
                        :class="period === '30days' ? 'active' : ''"
                    >30 Hari</button>
                </div>
            </div>
            <div class="chart-body">
                <canvas x-ref="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="activity-card">
        <div class="activity-header">
            <h3 class="activity-title">
                <i class="fas fa-history"></i> Aktivitas Terbaru
            </h3>
            <a href="{{ route('admin.photos.index') }}" class="view-all-link">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="activity-list">
            <template x-for="activity in recentActivities" :key="activity.id">
                <div class="activity-item">
                    <div class="activity-icon" :class="`activity-${activity.type}`">
                        <i :class="activity.icon"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-text" x-text="activity.description"></p>
                        <p class="activity-time" x-text="activity.time"></p>
                    </div>
                    <template x-if="activity.count">
                        <span class="activity-badge" x-text="activity.count"></span>
                    </template>
                </div>
            </template>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3 class="section-title">
            <i class="fas fa-bolt"></i> Quick Actions
        </h3>
        <div class="actions-grid">
            <a href="{{ route('admin.photos.index') }}" class="action-button">
                <i class="fas fa-images"></i>
                <span>Kelola Foto</span>
            </a>
            <a href="{{ route('admin.templates.create') }}" class="action-button">
                <i class="fas fa-plus"></i>
                <span>Buat Template</span>
            </a>
            <a href="{{ route('admin.templates.index') }}" class="action-button">
                <i class="fas fa-layer-group"></i>
                <span>Lihat Template</span>
            </a>
            <button @click="cleanupOldPhotos()" class="action-button">
                <i class="fas fa-broom"></i>
                <span>Cleanup Storage</span>
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function dashboardApp() {
    return {
        stats: @json($stats),
        period: '7days',
        lastUpdated: new Date().toLocaleString('id-ID', { 
            day: 'numeric', 
            month: 'short', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }),
        
        recentActivities: @json($recentActivities),
        
        
        trendChart: null,
        storageChart: null,
        
        init() {
            this.$nextTick(() => {
                this.initTrendChart();
                this.initStorageChart();
            });
        },
        
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },
        
        formatStorage(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        formatPercentage(used, total) {
            return ((used / total) * 100).toFixed(1);
        },
        
        initTrendChart() {
            const ctx = this.$refs.trendChart.getContext('2d');
            this.trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartData['labels'] ?? []),
                    datasets: [{
                        label: 'Upload Foto',
                        data: @json($chartData['data'] ?? []),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        },
    
        
        changePeriod(newPeriod) {
            this.period = newPeriod;
            // Fetch new data and update chart
            // this.fetchChartData(newPeriod);
        },
        
        cleanupOldPhotos() {
            if (confirm('Hapus foto yang lebih dari 30 hari? Tindakan ini tidak dapat dibatalkan.')) {
                // API call to cleanup
                alert('Fitur cleanup akan segera tersedia');
            }
        }
    }
}
</script>
@endpush