<?php
require_once 'includes/header.php';

// Fetch Logs
$stmt = $conn->query("
    SELECT al.*, u.full_name as admin_name 
    FROM admin_logs al 
    JOIN users u ON al.admin_id = u.id 
    ORDER BY al.created_at DESC 
    LIMIT 100
");
$logs = $stmt->fetchAll();

// Action → badge color mapping
function actionBadge($action) {
    $action_lower = mb_strtolower($action);
    if (str_contains($action_lower, 'xóa') || str_contains($action_lower, 'delete') || str_contains($action_lower, 'hủy')) {
        return 'bg-red-50 text-red-600';
    }
    if (str_contains($action_lower, 'thêm') || str_contains($action_lower, 'create') || str_contains($action_lower, 'tạo')) {
        return 'bg-emerald-50 text-emerald-600';
    }
    if (str_contains($action_lower, 'sửa') || str_contains($action_lower, 'update') || str_contains($action_lower, 'cập nhật')) {
        return 'bg-amber-50 text-amber-600';
    }
    if (str_contains($action_lower, 'login') || str_contains($action_lower, 'đăng nhập')) {
        return 'bg-blue-50 text-blue-600';
    }
    return 'bg-zinc-100 text-zinc-600';
}
?>

<!-- Page Header -->
<div class="mb-8 animate-fade-in">
    <div class="flex items-center gap-3 mb-1">
        <div class="w-10 h-10 rounded-xl bg-brand-gold/10 flex items-center justify-center">
            <i class="fas fa-history text-brand-gold"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-zinc-800 leading-snug">Nhật Ký Hệ Thống</h1>
            <p class="text-zinc-400 text-sm leading-relaxed">Lịch sử hoạt động của các quản trị viên.</p>
        </div>
    </div>
</div>

<!-- Logs Table -->
<div class="admin-card animate-fade-in">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="w-[180px]">Thời Gian</th>
                    <th class="w-[160px]">Quản Trị Viên</th>
                    <th class="w-[200px]">Hành Động</th>
                    <th>Chi Tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($logs)): ?>
                    <tr><td colspan="4" class="text-center text-zinc-400 py-12">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-clipboard-list text-3xl text-zinc-200"></i>
                            <span>Chưa có bản ghi nhật ký nào.</span>
                        </div>
                    </td></tr>
                <?php else: ?>
                    <?php foreach($logs as $log): ?>
                        <tr>
                            <td class="text-zinc-400 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-[10px] text-zinc-300"></i>
                                    <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-zinc-100 flex items-center justify-center text-[10px] font-bold text-zinc-500">
                                        <?= mb_substr($log['admin_name'], 0, 1) ?>
                                    </div>
                                    <span class="font-medium text-zinc-700"><?= htmlspecialchars($log['admin_name']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full leading-relaxed <?= actionBadge($log['action']) ?>">
                                    <?= htmlspecialchars($log['action']) ?>
                                </span>
                            </td>
                            <td class="text-zinc-500 leading-relaxed"><?= htmlspecialchars($log['details']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
