<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - REST API Assessment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #FAFAFA;
        }
    </style>
</head>
<body>
    <div class="min-h-screen">
     
        <div class="bg-white border-b border-gray-200">
            <div class="container mx-auto px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Monitoring</h1>
                <p class="text-sm text-gray-500 mt-1">REST API Assessment</p>
            </div>
        </div>

        <div class="container mx-auto px-6 py-8">
           
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600">Total Users</p>
                        <div class="w-10 h-10 bg-[#FFA726] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600">Total Transfers</p>
                        <div class="w-10 h-10 bg-[#FF9800] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_transfers'] }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600">Total Payments</p>
                        <div class="w-10 h-10 bg-[#FFB300] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_payments'] }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600">Total Top Ups</p>
                        <div class="w-10 h-10 bg-[#FFC107] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_topups'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-6 text-gray-900">Transfer Status</h2>
                    <canvas id="transferChart"></canvas>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-6 text-gray-900">Queue Status</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 bg-[#FFF8E1] border border-[#FFE082] rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Pending Transfers</span>
                            <span class="text-2xl font-bold text-[#F57C00]">{{ $stats['pending_transfers'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-[#FFF3E0] border border-[#FFCC80] rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Success Transfers</span>
                            <span class="text-2xl font-bold text-[#EF6C00]">{{ $stats['success_transfers'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-[#FBE9E7] border border-[#FFAB91] rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Failed Transfers</span>
                            <span class="text-2xl font-bold text-[#D84315]">{{ $stats['failed_transfers'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

          
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-bold mb-6 text-gray-900">Recent Transfers</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Transfer ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">From</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">To</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransfers as $transfer)
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-4 text-sm text-gray-900 font-mono">{{ substr($transfer->transfer_id, 0, 8) }}...</td>
                                <td class="px-4 py-4 text-sm text-gray-900">{{ $transfer->user->first_name }} {{ $transfer->user->last_name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-900">{{ $transfer->targetUser->first_name }} {{ $transfer->targetUser->last_name }}</td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($transfer->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-4">
                                    @if($transfer->status == 'SUCCESS')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#FFE082] text-[#E65100]">SUCCESS</span>
                                    @elseif($transfer->status == 'PENDING')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#FFF9C4] text-[#F57F17]">PENDING</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#FFCCBC] text-[#BF360C]">FAILED</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500">{{ optional($transfer->created_date)->format('Y-m-d H:i:s') ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">No transfers yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

           
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-6 text-gray-900">Pending Jobs</h2>
                    <div class="space-y-3">
                        @forelse($pendingJobs as $job)
                        <div class="p-4 bg-[#FFF8E1] border border-[#FFE082] rounded-lg">
                            <p class="text-sm font-medium text-gray-900">Job ID: {{ $job->id }}</p>
                            <p class="text-xs text-gray-500 mt-1">Created: {{ date('Y-m-d H:i:s', $job->created_at) }}</p>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">No pending jobs</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-6 text-gray-900">Failed Jobs</h2>
                    <div class="space-y-3">
                        @forelse($failedJobs as $job)
                        <div class="p-4 bg-[#FBE9E7] border border-[#FFAB91] rounded-lg">
                            <p class="text-sm font-medium text-gray-900">Job Failed</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $job->failed_at }}</p>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">No failed jobs</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const stats = @json($stats);
        
        const ctx = document.getElementById('transferChart').getContext('2d');
        const transferChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Success', 'Failed'],
                datasets: [{
                    data: [
                        stats.pending_transfers || 0,
                        stats.success_transfers || 0,
                        stats.failed_transfers || 0
                    ],
                    backgroundColor: [
                        '#FFC107',
                        '#FF9800',
                        '#FF5722'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
        
        setTimeout(function(){
            location.reload();
        }, 30000);
    </script>
</body>
</html>