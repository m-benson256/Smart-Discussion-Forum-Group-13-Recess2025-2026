<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Discussion Forum · Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #721206c5; display: flex; min-height: 100vh; color: #0f272a; }

        .sidebar {
            width: 270px;
            background: #041e82;
            color: #062042;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            flex-shrink: 0;
            border-right: 1px solid #1e293b;
            z-index: 100;
        }
        .sidebar-brand {
            font-weight: 700;
            font-size: 18px;
            padding-bottom: 28px;
            border-bottom: 1px solid #1e293b;
            margin-bottom: 24px;
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-brand i { color: #38bdf8; font-size: 26px; }
        .sidebar-brand span { background: #1e353b; padding: 2px 10px; border-radius: 20px; font-size: 10px; color: #94a3b8; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 11px 16px;
            border-radius: 10px;
            margin-bottom: 2px;
            font-weight: 500;
            font-size: 14px;
            transition: 0.2s;
            cursor: pointer;
            color: #94a3b8;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }
        .nav-item i { width: 22px; font-size: 15px; color: #475569; flex-shrink: 0; }
        .nav-item.active { background: #1e293b; color: white; }
        .nav-item.active i { color: #38bdf8; }
        .nav-item:not(.active):hover { background: #1e293b; color: #f1f5f9; }
        .nav-item:not(.active):hover i { color: #94a3b8; }
        .nav-item .badge-nav { margin-left: auto; background: #dc2626; color: white; font-size: 10px; padding: 2px 8px; border-radius: 12px; min-width: 20px; text-align: center; }
        .nav-divider { flex: 1; }
        .nav-item.logout { margin-top: 8px; border-top: 1px solid #1e293b; padding-top: 16px; color: #f87171; }
        .nav-item.logout i { color: #f87171; }

        .main { flex: 1; padding: 24px 32px; overflow-x: auto; min-width: 0; }
        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
        }
        .top-bar h1 { font-weight: 600; font-size: 24px; }
        .top-bar .badge { background: #e2e8f0; padding: 6px 16px; border-radius: 40px; font-size: 13px; color: #1e293b; }

        .page-panel { display: none; animation: fadeIn 0.3s ease; }
        .page-panel.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }
        .kpi-card {
            background: white;
            border-radius: 16px;
            padding: 18px 20px;
            border: 1px solid #e9edf2;
            transition: 0.2s;
            cursor: pointer;
        }
        .kpi-card:hover { border-color: #94a3b8; transform: translateY(-2px); }
        .kpi-card .kpi-icon { float: right; font-size: 28px; color: #b9c7da; }
        .kpi-card .kpi-label { font-weight: 500; color: #64748b; font-size: 13px; margin-bottom: 4px; }
        .kpi-card .kpi-number { font-weight: 700; font-size: 30px; color: #0f172a; }
        .kpi-card .kpi-sub { font-size: 12px; color: #64748b; margin-top: 4px; }

        .chart-row {
            display: grid;
            grid-template-columns: 1.2fr 0.9fr 1fr;
            gap: 18px;
            margin-bottom: 28px;
        }
        .chart-box {
            background: white;
            border-radius: 16px;
            padding: 16px 18px 12px;
            border: 1px solid #e9edf2;
            cursor: pointer;
            transition: 0.2s;
            position: relative;
        }
        .chart-box:hover { border-color: #2563eb; box-shadow: 0 4px 12px rgba(37,99,235,0.1); }
        .chart-box .expand-btn {
            position: absolute;
            top: 10px;
            right: 12px;
            background: #f1f5f9;
            border: none;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            color: #64748b;
            cursor: pointer;
        }
        .chart-box .expand-btn:hover { background: #e2e8f0; }
        .chart-box h3 { font-weight: 600; font-size: 14px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .chart-container { position: relative; height: 140px; }

        .panel {
            background: white;
            border-radius: 16px;
            border: 1px solid #e9edf2;
            padding: 16px 20px 20px;
            margin-bottom: 16px;
        }
        .panel-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 12px; flex-wrap: wrap; gap: 8px;
        }
        .panel-header h3 { font-weight: 600; font-size: 15px; }
        .panel-header .badge-action { background: #f1f5f9; padding: 4px 14px; border-radius: 40px; font-size: 12px; font-weight: 500; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 8px 6px 8px 0; font-weight: 600; color: #475569; border-bottom: 1px solid #e9edf2; }
        td { padding: 10px 6px 10px 0; border-bottom: 1px solid #f1f5f9; color: #1e293b; }

        .status-badge {
            font-size: 11px; padding: 3px 12px; border-radius: 40px; font-weight: 500; display: inline-block;
        }
        .status-badge.active { background: #dcfce7; color: #15803d; }
        .status-badge.inactive { background: #fee2e2; color: #991b1b; }
        .status-badge.warned { background: #fef3c7; color: #92400e; }
        .status-badge.blocked { background: #fecaca; color: #991b1b; }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.verified { background: #dbeafe; color: #1e40af; }
        .status-badge.unverified { background: #fee2e2; color: #991b1b; }
        .status-badge.resolved { background: #dcfce7; color: #15803d; }
        .status-badge.dismissed { background: #f1f5f9; color: #64748b; }
        .status-badge.public { background: #dbeafe; color: #1e40af; }
        .status-badge.private { background: #fef3c7; color: #92400e; }
        .status-badge.approved { background: #dcfce7; color: #15803d; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }

        .role-badge { padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-block; }
        .role-student { background: #dbeafe; color: #1e40af; }
        .role-lecturer { background: #fef3c7; color: #92400e; }
        .role-staff { background: #d1fae5; color: #065f46; }
        .role-moderator { background: #e0e7ff; color: #3730a3; }
        .role-admin { background: #fce7f3; color: #9d174d; }

        .user-avatar {
            width: 32px; height: 32px; border-radius: 40px; display: inline-flex;
            align-items: center; justify-content: center; color: white;
            font-weight: 600; font-size: 13px; flex-shrink: 0;
        }
        .user-cell { display: flex; align-items: center; gap: 10px; }

        .action-btn {
            background: transparent;
            border: 1px solid #d1d9e6;
            padding: 3px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 500;
            color: #1e293b;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            position: relative;
        }
        .action-btn:hover { background: #f1f5f9; transform: scale(1.02); }
        .action-btn.primary { background: #dbeafe; border-color: #2563eb; color: #1e40af; }
        .action-btn.success { background: #dcfce7; border-color: #15803d; color: #15803d; }
        .action-btn.danger { background: #fee2e2; border-color: #991b1b; color: #991b1b; }
        .action-btn.warning { background: #fef3c7; border-color: #92400e; color: #92400e; }
        .action-btn .tooltip {
            visibility: hidden;
            opacity: 0;
            width: 120px;
            background: #0f172a;
            color: white;
            text-align: center;
            border-radius: 6px;
            padding: 4px 8px;
            position: absolute;
            z-index: 1000;
            bottom: 130%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            font-weight: 400;
            transition: 0.2s;
            pointer-events: none;
        }
        .action-btn .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: #0f172a;
        }
        .action-btn:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .placeholder-content {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e9edf2;
        }
        .placeholder-content h2 { margin-bottom: 6px; }
        .placeholder-content .actions {
            margin: 14px 0 18px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 6px 18px;
            border-radius: 40px;
            border: 1px solid #d1d9e6;
            background: white;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary { background: #2563eb; color: white; border-color: #2563eb; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-success { background: #22c55e; color: white; border-color: #22c55e; }
        .btn-success:hover { background: #16a34a; }
        .btn-danger { background: #ef4444; color: white; border-color: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning { background: #f59e0b; color: white; border-color: #f59e0b; }
        .btn-warning:hover { background: #d97706; }
        .btn-outline { background: transparent; border-color: #d1d9e6; }

        .modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 2000;
        }
        .modal.show { display: flex; }
        .modal-content {
            background: white; border-radius: 16px; padding: 28px; max-width: 520px; width: 90%;
            max-height: 90vh; overflow-y: auto;
        }
        .modal-content h3 { margin-bottom: 16px; }
        .modal-content label { display: block; margin: 8px 0 4px; font-size: 13px; font-weight: 500; color: #475569; }
        .modal-content input, .modal-content select, .modal-content textarea {
            width: 100%; padding: 8px 12px; border: 1px solid #d1d9e6;
            border-radius: 8px; font-size: 13px; font-family: inherit;
        }
        .modal-content textarea { resize: vertical; min-height: 60px; }
        .modal-actions { margin-top: 16px; display: flex; gap: 10px; justify-content: flex-end; }

        .feed-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: 0.2s;
        }
        .feed-item:hover { background: #f8fafc; padding-left: 8px; border-radius: 8px; }
        .feed-item:last-child { border-bottom: none; }

        .settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-top: 12px; }
        .settings-card { background: #f8fafc; border-radius: 14px; padding: 18px; border: 1px solid #e9edf2; }
        .settings-card h4 { margin-bottom: 10px; color: #0f172a; font-size: 14px; }
        .settings-card label { display: block; margin: 6px 0 3px; font-size: 12px; font-weight: 500; color: #475569; }
        .settings-card input, .settings-card select {
            width: 100%; padding: 6px 12px; border: 1px solid #d1d9e6;
            border-radius: 8px; font-size: 13px; font-family: inherit;
        }
        .settings-card .btn { margin-top: 10px; }

        .topics-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
        .topic-card {
            background: #f8fafc; border-radius: 12px; padding: 14px 16px;
            border: 1px solid #e9edf2; display: flex; justify-content: space-between; align-items: center;
            transition: 0.2s;
        }
        .topic-card:hover { border-color: #94a3b8; }
        .topic-card .topic-info h4 { font-size: 14px; margin-bottom: 2px; }
        .topic-card .topic-info p { font-size: 12px; color: #64748b; }

        .appeal-box {
            background: #f8fafc; border-radius: 10px; padding: 12px 16px;
            border-left: 4px solid #f59e0b; margin-bottom: 8px;
        }
        .appeal-box .appeal-meta { font-size: 12px; color: #64748b; margin-top: 4px; }

        .stats-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .stat-card {
            background: #f8fafc; border-radius: 12px; padding: 16px; text-align: center;
            cursor: pointer; transition: 0.2s;
        }
        .stat-card:hover { background: #e2e8f0; transform: scale(1.02); }
        .stat-card .stat-number { font-size: 28px; font-weight: 700; }
        .stat-card .stat-label { font-size: 13px; color: #64748b; }

        .user-detail-modal .detail-row { display: flex; padding: 6px 0; border-bottom: 1px solid #f1f5f9; }
        .user-detail-modal .detail-row .label { font-weight: 600; width: 120px; color: #475569; }
        .user-detail-modal .detail-row .value { flex: 1; }

        .color-preview {
            display: inline-block; width: 30px; height: 30px; border-radius: 8px;
            margin-left: 10px; border: 2px solid #e2e8f0; vertical-align: middle;
        }

        .search-box {
            padding: 6px 14px; border: 1px solid #d1d9e6; border-radius: 30px;
            font-size: 13px; width: 200px; transition: 0.2s;
        }
        .search-box:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

        .role-filter { padding: 6px 12px; border: 1px solid #d1d9e6; border-radius: 30px; font-size: 13px; background: white; }

        .warning-rule {
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px 16px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 8px;
        }
        .warning-rule .rule-title { font-weight: 600; font-size: 14px; }
        .warning-rule .rule-desc { font-size: 13px; color: #64748b; margin-top: 2px; }

        @media (max-width: 1200px) {
            .chart-row { grid-template-columns: 1fr 1fr; }
            .chart-row .chart-box:last-child { grid-column: span 2; }
        }
        @media (max-width: 992px) {
            .kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .settings-grid { grid-template-columns: 1fr; }
            .topics-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { width: 220px; padding: 16px 12px; }
            .sidebar-brand span { font-size: 13px; }
            .nav-item { font-size: 13px; padding: 10px 12px; }
            .main { padding: 16px; }
            .chart-row { grid-template-columns: 1fr; }
            .chart-row .chart-box:last-child { grid-column: span 1; }
            .search-box { width: 140px; }
        }
        @media (max-width: 480px) {
            .kpi-grid { grid-template-columns: 1fr; }
            .sidebar { width: 60px; padding: 12px 8px; }
            .sidebar-brand span, .nav-item span { display: none; }
            .nav-item { justify-content: center; padding: 12px 4px; }
            .nav-item i { width: auto; font-size: 18px; }
            .main { padding: 12px; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-users-between-lines"></i>
            <span>Smart Discussion Forum</span>
        </div>
        <button class="nav-item active" data-page="overview">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </button>
        <button class="nav-item" data-page="users">
            <i class="fas fa-users"></i>
            <span>Users</span>
            <span class="badge-nav" id="userCount">9</span>
        </button>
        <button class="nav-item" data-page="groups">
            <i class="fas fa-layer-group"></i>
            <span>Groups</span>
            <span class="badge-nav" id="groupCount">3</span>
        </button>
        <button class="nav-item" data-page="topics">
            <i class="fas fa-tags"></i>
            <span>Topics</span>
            <span class="badge-nav" id="topicCount">8</span>
        </button>
        <button class="nav-item" data-page="warnings">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Warnings</span>
            <span class="badge-nav" id="warningCount">3</span>
        </button>
        <button class="nav-item" data-page="settings">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </button>
        <div class="nav-divider"></div>
        <button class="nav-item logout" data-page="logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>LogOut</span>
        </button>
    </aside>

    <!-- Main Content -->
    <main class="main" id="mainContent">
        <div class="top-bar">
            <h1 id="pageTitle"><i class="fas fa-gauge-high" style="color: #2563eb; margin-right: 10px;"></i>Dashboard Overview</h1>
        </div>

        <!-- ===== OVERVIEW ===== -->
        <div class="page-panel active" id="page-overview">
            <div class="kpi-grid" id="kpiGrid">
                <div class="kpi-card" onclick="navigateTo('users')">
                    <div class="kpi-icon"><i class="fas fa-users"></i></div>
                    <div class="kpi-label">Total Users</div>
                    <div class="kpi-number" id="kpiTotalUsers">9</div>
                    <div class="kpi-sub"><i class="fas fa-arrow-up" style="color:#16a34a;"></i> +2 this month</div>
                </div>
                <div class="kpi-card" onclick="navigateTo('users')">
                    <div class="kpi-icon"><i class="fas fa-user-check"></i></div>
                    <div class="kpi-label">Active Users</div>
                    <div class="kpi-number" id="kpiActiveUsers">5</div>
                    <div class="kpi-sub">56% of total</div>
                </div>
                <div class="kpi-card" onclick="navigateTo('users')">
                     <div class="kpi-icon"><i class="fas fa-user-clock"></i></div>
                    <div class="kpi-label">Inactive Users</div>
                    <div class="kpi-number" id="kpiInactiveUsers">2</div>
                    <div class="kpi-sub">Last 30 days</div>
                </div>
                <div class="kpi-card" onclick="navigateTo('users')">
                    <div class="kpi-icon"><i class="fas fa-ban"></i></div>
                    <div class="kpi-label">Blocked Users</div>
                    <div class="kpi-number" id="kpiBlockedUsers">2</div>
                    <div class="kpi-sub"><i class="fas fa-exclamation-circle" style="color:#dc2626;"></i> Awaiting review</div>
                </div>
            </div>

            <div class="chart-row">
                <div class="chart-box" onclick="expandChart('activity')">
                    <button class="expand-btn" onclick="event.stopPropagation(); expandChart('activity')"><i class="fas fa-expand"></i> View</button>
                    <h3><i class="fas fa-chart-line"></i> User Activity (Last 7 Days)</h3>
                    <div class="chart-container"><canvas id="activityChart"></canvas></div>
                </div>
                <div class="chart-box" onclick="expandChart('topics')">
                    <button class="expand-btn" onclick="event.stopPropagation(); expandChart('topics')"><i class="fas fa-expand"></i> View</button>
                    <h3><i class="fas fa-chart-bar"></i> Top Discussion Analytics</h3>
                    <div class="chart-container"><canvas id="topicsChart"></canvas></div>
                </div>
                <div class="chart-box" onclick="expandChart('distribution')">
                    <button class="expand-btn" onclick="event.stopPropagation(); expandChart('distribution')"><i class="fas fa-expand"></i> View</button>
                    <h3><i class="fas fa-chart-pie"></i> User Status Distribution</h3>
                    <div class="chart-container"><canvas id="distChart"></canvas></div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3><i class="fas fa-clock" style="color: #f59e0b;"></i> Recent Inactive Users</h3>
                    <span class="badge-action"><i class="fas fa-exclamation-triangle"></i> <span id="atRiskCount">2</span> at risk</span>
                </div>
                <div id="inactiveFeed"></div>
            </div>
        </div>

        <!-- ===== USERS ===== -->
        <div class="page-panel" id="page-users">
            <div class="placeholder-content">
                <h2><i class="fas fa-users" style="color:#2563eb;"></i> User Management</h2>
                <p>View all registered students and lecturers. Admins can verify lecturer accounts, while student accounts remain managed without verification.</p>
                <div class="actions">
                    <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                        <input class="search-box" id="userSearch" placeholder="Search users..." oninput="filterUsers()">
                        <select class="role-filter" id="roleFilter" onchange="filterUsers()">
                            <option value="all">All Roles</option>
                            <option value="student">Students</option>
                            <option value="lecturer">Lecturers</option>
                        </select>
                    </div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>#</th><th>Full Name</th><th>Email</th><th>Role</th><th>Status</th><th>Verification</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="userTableBody"></tbody>
                    </table>
                </div>
                <div style="margin-top:12px; font-size:13px; color:#64748b;">Showing <span id="userShowCount">9</span> users</div>
            </div>
        </div>

        <!-- ===== GROUPS ===== -->
        <div class="page-panel" id="page-groups">
            <div class="placeholder-content">
                <h2><i class="fas fa-layer-group" style="color:#2563eb;"></i> Group Management</h2>
                <p>View all groups created by students. Groups show member counts and activity.</p>
                <div class="actions">
                    <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                        <input class="search-box" id="groupSearch" placeholder="Search groups..." oninput="filterGroups()">
                    </div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>Group Name</th><th>Description</th><th>Members</th><th>Posts/Week</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="groupTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ===== TOPICS ===== -->
        <div class="page-panel" id="page-topics">
            <div class="placeholder-content">
                <h2><i class="fas fa-tags" style="color:#2563eb;"></i> Topic Moderation</h2>
                <p>View all topics created by students. Topics can be flagged for review or bulk deleted.</p>
                <div class="actions">
                    <button class="btn btn-danger" onclick="bulkDeleteTopics()"><i class="fas fa-trash"></i> Bulk Delete</button>
                    <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                        <input class="search-box" id="topicSearch" placeholder="Search topics..." oninput="filterTopics()">
                    </div>
                </div>

                <div id="topicsContainer">
                    <h4 style="margin:16px 0 10px;">All Topics</h4>
                    <div class="topics-grid" id="topicsGrid"></div>

                    <h4 style="margin:18px 0 10px;">Pending Moderation</h4>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Topic</th><th>Category</th><th>Author</th><th>Reported</th><th>Status</th><th>Actions</th></tr></thead>
                            <tbody id="pendingTopicsBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== WARNINGS ===== -->
        <div class="page-panel" id="page-warnings">
            <div class="placeholder-content">
                <h2><i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i> Warning Management</h2>
                <p>Track issued warnings (maximum 3 per user), manage escalation rules, and handle appeals.</p>
                <div class="actions">
                    <button class="btn btn-warning" onclick="openModal('warning')"><i class="fas fa-plus"></i> Issue Warning</button>
                    <button class="btn"><i class="fas fa-history"></i> View History</button>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>User</th><th>Warning #</th><th>Reason</th><th>Issued</th><th>Expires</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="warningTableBody"></tbody>
                    </table>
                </div>

                <h4 style="margin:18px 0 10px;">Pending Appeals</h4>
                <div id="appealsContainer"></div>

                <div style="margin-top:16px; background:#f8fafc; border-radius:12px; padding:16px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                    <div>
                        <label style="font-size:12px; font-weight:500;">Warning → Suspension</label>
                        <select style="width:100%; padding:6px 12px; border-radius:8px; border:1px solid #d1d9e6;">
                            <option>After 3 warnings</option>
                            <option selected>After 5 warnings</option>
                            <option>After 10 warnings</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:12px; font-weight:500;">Suspension Duration</label>
                        <select style="width:100%; padding:6px 12px; border-radius:8px; border:1px solid #d1d9e6;">
                            <option>7 days</option>
                            <option selected>14 days</option>
                            <option>30 days</option>
                            <option>Permanent</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:12px; font-weight:500;">Appeal Response Time</label>
                        <select style="width:100%; padding:6px 12px; border-radius:8px; border:1px solid #d1d9e6;">
                            <option>24 hours</option>
                            <option selected>48 hours</option>
                            <option>72 hours</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" style="margin-top:12px;"><i class="fas fa-save"></i> Save Rules</button>
            </div>
        </div>

        <!-- ===== SETTINGS ===== -->
        <div class="page-panel" id="page-settings">
            <div class="placeholder-content">
                <h2><i class="fas fa-cog" style="color:#2563eb;"></i> Platform Settings</h2>
                <p>Configure general settings, security, content policies, and backups.</p>

                <div class="settings-grid">
                    <div class="settings-card">
                        <h4>General Settings</h4>
                        <label>Site Name</label>
                        <input type="text" value="Smart Discussion Forum" id="siteName">
                        <label>Theme Color</label>
                        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                            <input type="color" id="themeColor" value="#2563eb" onchange="applyThemeColor()">
                            <span id="colorPreview" class="color-preview" style="background:#2563eb;"></span>
                        </div>
                        <label>Default Language</label>
                        <select><option>English</option><option>French</option><option>Spanish</option></select>
                        <button class="btn btn-primary" onclick="saveSettings()"><i class="fas fa-save"></i> Save</button>
                    </div>
                    <div class="settings-card">
                        <h4>Security Options</h4>
                        <label>Password Policy</label>
                        <select><option>Standard (8+ chars)</option><option selected>Strong (12+ chars, symbols)</option></select>
                        <label>2FA Enforcement</label>
                        <select><option>Disabled</option><option selected>Optional</option><option>Required</option></select>
                        <label>Session Timeout</label>
                        <select><option>30 minutes</option><option selected>60 minutes</option><option>120 minutes</option></select>
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                    <div class="settings-card">
                        <h4>Content Policies</h4>
                        <label>Word Filters</label>
                        <input type="text" value="spam, offensive, inappropriate">
                        <label>Posting Limits (per day)</label>
                        <select><option>10</option><option selected>25</option><option>50</option><option>Unlimited</option></select>
                        <label>Auto-moderation</label>
                        <select><option>Disabled</option><option selected>Basic</option><option>Strict</option></select>
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                    <div class="settings-card">
                        <h4>Backup & Restore</h4>
                        <label>Auto-backup Frequency</label>
                        <select><option>Daily</option><option selected>Weekly</option><option>Monthly</option></select>
                        <label>Backup Location</label>
                        <select><option>Local</option><option selected>Cloud</option><option>Both</option></select>
                        <button class="btn btn-success"><i class="fas fa-database"></i> Create Backup</button>
                        <button class="btn btn-warning"><i class="fas fa-undo"></i> Restore</button>
                    </div>
                </div>

                <div style="margin-top:18px; background:#fee2e2; border-radius:12px; padding:16px; border:1px solid #fecaca;">
                    <h4 style="color:#991b1b;"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h4>
                    <p style="font-size:13px; color:#64748b;">These actions are irreversible and will permanently delete data.</p>
                    <button class="btn btn-danger" style="margin-top:8px;" onclick="if(confirm('Are you sure?')) alert('All data reset!')"><i class="fas fa-database"></i> Reset All Data</button>
                    <button class="btn btn-danger" style="margin-top:8px; margin-left:8px;" onclick="if(confirm('Purge all inactive users?')) { users = users.filter(u => u.status !== 'inactive'); renderUsers(); updateKPIs(); updateBadges(); }"><i class="fas fa-users-slash"></i> Purge All Inactive</button>
                </div>
            </div>
        </div>

        <!-- ===== LOGOUT ===== -->
        <div class="page-panel" id="page-logout">
            <div class="placeholder-content" style="text-align:center;">
                <i class="fas fa-sign-out-alt" style="font-size:48px; color:#f87171; display:block; margin-bottom:16px;"></i>
                <h2>Log Out</h2>
                <p>This will clear your session token and return you to the login portal.</p>
                <div class="actions" style="justify-content:center;">
                    <button class="btn btn-danger" onclick="alert('Session terminated. Redirecting to login...')"><i class="fas fa-sign-out-alt"></i> Confirm Logout</button>
                    <button class="btn" onclick="navigateTo('overview')">Cancel</button>
                </div>
            </div>
        </div>
    </main>

    <!-- ===== MODAL ===== -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <h3 id="modalTitle">Add New</h3>
            <div id="modalBody"></div>
            <div class="modal-actions">
                <button class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveModal()">Save</button>
            </div>
        </div>
    </div>

    <!-- ===== USER DETAIL MODAL ===== -->
    <div class="modal user-detail-modal" id="userDetailModal">
        <div class="modal-content">
            <h3><i class="fas fa-user"></i> User Details</h3>
            <div id="userDetailBody"></div>
            <div class="modal-actions">
                <button class="btn btn-outline" onclick="document.getElementById('userDetailModal').classList.remove('show')">Close</button>
            </div>
        </div>
    </div>

    <!-- ===== EXPANDED CHART MODAL ===== -->
    <div class="modal" id="chartModal">
        <div class="modal-content" style="max-width:800px;">
            <h3 id="chartModalTitle">Chart</h3>
            <div style="height:400px; position:relative;"><canvas id="expandedChart"></canvas></div>
            <div class="modal-actions">
                <button class="btn btn-outline" onclick="document.getElementById('chartModal').classList.remove('show')">Close</button>
            </div>
        </div>
    </div>

    <script>
        // ===== DATA STORE =====
        let users = {!! $usersJson !!};

        // Groups - created by students
        let groups = [
            { id: 1, name: 'CS 2024 Study Group', description: 'Computer Science study group', members: 45, posts: 128, status: 'active' },
            { id: 2, name: 'Math Tutorial Forum', description: 'Advanced mathematics help', members: 28, posts: 67, status: 'active' },
            { id: 3, name: 'Physics Discussion', description: 'Physics problem solving', members: 15, posts: 45, status: 'warned' }
        ];

        // Topics - created by students
        let topics = [
            { id: 1, name: 'Data Analysis', category: 'Academic', replies: 89, views: 1200, engagement: 68, status: 'active', author: 'John Doe' },
            { id: 2, name: 'Technical Analysis', category: 'Academic', replies: 67, views: 890, engagement: 54, status: 'active', author: 'Jane Smith' },
            { id: 3, name: 'Data Management', category: 'Academic', replies: 112, views: 1800, engagement: 82, status: 'active', author: 'Chris Brown' },
            { id: 4, name: 'Communication Skills', category: 'General', replies: 54, views: 670, engagement: 43, status: 'active', author: 'Amanda Lee' },
            { id: 5, name: 'SPAM: Free Resources', category: 'Spam', replies: 0, views: 45, engagement: 0, status: 'flagged', author: 'Anonymous' },
            { id: 6, name: 'Offensive Content', category: 'Inappropriate', replies: 3, views: 120, engagement: 15, status: 'pending', author: 'UserX' },
            { id: 7, name: 'AI Ethics Discussion', category: 'Academic', replies: 34, views: 560, engagement: 72, status: 'active', author: 'Sarah Wilson' },
            { id: 8, name: 'Duplicate Thread', category: 'General', replies: 2, views: 30, engagement: 10, status: 'flagged', author: 'Student102' }
        ];

        // Warnings - max 3 per user
        let warnings = [
            { id: 1, user: 'Mike Wilson', number: 1, reason: 'Spam', issued: '2026-06-20', expires: '2026-07-20', status: 'active' },
            { id: 2, user: 'Sarah Lee', number: 2, reason: 'Harassment', issued: '2026-06-15', expires: '2026-07-15', status: 'active' },
            { id: 3, user: 'Emily Davis', number: 1, reason: 'Academic misconduct', issued: '2026-06-25', expires: '2026-07-25', status: 'pending' }
        ];

        let appeals = [
            { id: 1, user: 'Emily Davis', warning: 'Academic misconduct', submitted: '2026-06-26' }
        ];

        let nextIds = { user: 10, group: 4, topic: 9, warning: 4, appeal: 2 };

        // ===== RENDER FUNCTIONS =====
        function renderUsers() {
            const search = document.getElementById('userSearch')?.value?.toLowerCase() || '';
            const roleFilter = document.getElementById('roleFilter')?.value || 'all';
            
            let filtered = users.filter(u => {
                const matchName = u.name.toLowerCase().includes(search) || u.email.toLowerCase().includes(search);
                const matchRole = roleFilter === 'all' || u.role === roleFilter;
                return matchName && matchRole;
            });

            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = filtered.map((u, index) => {
                let verificationBadge = '';
                if (u.role === 'lecturer') {
                    if (u.verification_status === 'pending') {
                        verificationBadge = `<span class="status-badge pending">Pending</span>`;
                    } else if (u.verification_status === 'approved') {
                        verificationBadge = `<span class="status-badge approved">Verified</span>`;
                    } else {
                        verificationBadge = `<span class="status-badge unverified">Unverified</span>`;
                    }
                } else {
                    verificationBadge = `<span class="status-badge verified">Not Required</span>`;
                }

                let actionButtons = `
                    <button class="action-btn" onclick="viewUser(${u.id})"><i class="fas fa-eye"></i><span class="tooltip">View User</span></button>
                `;

                if (u.role === 'lecturer' && u.verification_status === 'pending') {
                    actionButtons += `
                        <button class="action-btn success" onclick="verifyLecturer(${u.id})"><i class="fas fa-check"></i><span class="tooltip">Verify Lecturer</span></button>
                        <button class="action-btn danger" onclick="rejectLecturer(${u.id})"><i class="fas fa-times"></i><span class="tooltip">Reject</span></button>
                    `;
                }
                
                if (u.status === 'blocked') {
                    actionButtons += `
                        <button class="action-btn warning" onclick="unblockUser(${u.id})"><i class="fas fa-unlock"></i><span class="tooltip">Unblock</span></button>
                    `;
                } else {
                    actionButtons += `
                        <button class="action-btn danger" onclick="blockUser(${u.id})"><i class="fas fa-ban"></i><span class="tooltip">Block</span></button>
                    `;
                }

                return `
                <tr onclick="viewUser(${u.id})" style="cursor:pointer;">
                    <td>${index + 1}</td>
                    <td><div class="user-cell"><span class="user-avatar" style="background:${getColor(u.name)};">${u.name.split(' ').map(n => n[0]).join('')}</span> ${u.name}</div></td>
                    <td>${u.email}</td>
                    <td><span class="role-badge role-${u.role}">${u.role.charAt(0).toUpperCase() + u.role.slice(1)}</span></td>
                    <td><span class="status-badge ${u.status}">${u.status.charAt(0).toUpperCase() + u.status.slice(1)}</span></td>
                    <td>${verificationBadge}</td>
                    <td onclick="event.stopPropagation();">${actionButtons}</td>
                </tr>
            `}).join('');
            document.getElementById('userShowCount').textContent = filtered.length;
            document.getElementById('userCount').textContent = users.length;
            updateKPIs();
        }

        function filterUsers() { renderUsers(); }

        function renderGroups() {
            const search = document.getElementById('groupSearch')?.value?.toLowerCase() || '';
            
            let filtered = groups.filter(g => {
                return g.name.toLowerCase().includes(search) || g.description.toLowerCase().includes(search);
            });

            const tbody = document.getElementById('groupTableBody');
            tbody.innerHTML = filtered.map(g => {
                let groupAction = '';
                if (g.status === 'active') {
                    groupAction = `
                        <button class="action-btn warning" onclick="warnGroup(${g.id})"><i class="fas fa-exclamation-triangle"></i><span class="tooltip">Warn Group</span></button>
                        <button class="action-btn danger" onclick="blockGroup(${g.id})"><i class="fas fa-ban"></i><span class="tooltip">Block Group</span></button>
                    `;
                } else if (g.status === 'warned') {
                    groupAction = `
                        <button class="action-btn danger" onclick="blockGroup(${g.id})"><i class="fas fa-ban"></i><span class="tooltip">Block Group</span></button>
                    `;
                } else {
                    groupAction = `
                        <button class="action-btn success" onclick="toggleGroupStatus(${g.id})"><i class="fas fa-unlock"></i><span class="tooltip">Unblock</span></button>
                    `;
                }
                return `
                <tr>
                    <td><strong>${g.name}</strong></td>
                    <td>${g.description}</td>
                    <td>${g.members}</td>
                    <td>${g.posts}</td>
                    <td><span class="status-badge ${g.status}">${g.status.charAt(0).toUpperCase() + g.status.slice(1)}</span></td>
                    <td>
                        <button class="action-btn" onclick="viewGroup(${g.id})"><i class="fas fa-eye"></i><span class="tooltip">View Group</span></button>
                        ${groupAction}
                    </td>
                </tr>
            `;
            }).join('');
            document.getElementById('groupCount').textContent = groups.length;
        }

        function filterGroups() { renderGroups(); }

        let groupedView = false;

        function renderTopics() {
            const search = document.getElementById('topicSearch')?.value?.toLowerCase() || '';
            
            let allTopics = topics.filter(t => {
                return t.name.toLowerCase().includes(search) || t.author.toLowerCase().includes(search) || t.category.toLowerCase().includes(search);
            });

            let activeTopics = allTopics.filter(t => t.status === 'active');
            let flaggedTopics = allTopics.filter(t => t.status === 'flagged' || t.status === 'pending');

            // Render active topics
            const grid = document.getElementById('topicsGrid');
            if (activeTopics.length === 0) {
                grid.innerHTML = '<div style="grid-column:1/-1; padding:20px; text-align:center; color:#64748b;">No active topics found.</div>';
            } else {
                grid.innerHTML = activeTopics.map(t => `
                    <div class="topic-card">
                        <div class="topic-info">
                            <h4>${t.name} <span class="status-badge" style="background:#dbeafe;color:#1e40af;font-size:10px;">${t.category}</span></h4>
                            <p>${t.replies} replies · ${t.views} views · ${t.engagement}% engagement</p>
                            <p style="font-size:11px; color:#94a3b8;">By: ${t.author}</p>
                        </div>
                        <div>
                            <button class="action-btn warning" onclick="flagTopic(${t.id})"><i class="fas fa-flag"></i><span class="tooltip">Flag Topic</span></button>
                            <button class="action-btn" onclick="viewTopic(${t.id})"><i class="fas fa-eye"></i><span class="tooltip">View Topic</span></button>
                            <input type="checkbox" class="topic-select" data-id="${t.id}" style="margin-left:8px;">
                        </div>
                    </div>
                `).join('');
            }

            // Render flagged/pending topics
            const pendingBody = document.getElementById('pendingTopicsBody');
            if (flaggedTopics.length === 0) {
                pendingBody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:#64748b; padding:20px;">No pending moderation topics.</td></tr>';
            } else {
                pendingBody.innerHTML = flaggedTopics.map(t => `
                    <tr>
                        <td>${t.name}</td>
                        <td><span class="status-badge" style="background:${t.category === 'Spam' ? '#fecaca' : '#fef3c7'};color:${t.category === 'Spam' ? '#991b1b' : '#92400e'};">${t.category}</span></td>
                        <td>${t.author}</td>
                        <td>2026-06-${Math.floor(Math.random() * 20) + 10}</td>
                        <td><span class="status-badge ${t.status === 'flagged' ? 'blocked' : 'pending'}">${t.status.charAt(0).toUpperCase() + t.status.slice(1)}</span></td>
                        <td>
                            <button class="action-btn success" onclick="approveTopic(${t.id})"><i class="fas fa-check"></i><span class="tooltip">Approve</span></button>
                            <button class="action-btn danger" onclick="deleteTopic(${t.id})"><i class="fas fa-trash"></i><span class="tooltip">Delete</span></button>
                        </td>
                    </tr>
                `).join('');
            }
            document.getElementById('topicCount').textContent = topics.length;
        }

        function filterTopics() { renderTopics(); }

        function renderWarnings() {
            const tbody = document.getElementById('warningTableBody');
            tbody.innerHTML = warnings.map(w => `
                <tr>
                    <td>${w.user}</td>
                    <td><span class="status-badge warned">#${w.number}</span></td>
                    <td>${w.reason}</td>
                    <td>${w.issued}</td>
                    <td>${w.expires}</td>
                    <td><span class="status-badge ${w.status}">${w.status.charAt(0).toUpperCase() + w.status.slice(1)}</span></td>
                    <td>
                        ${w.status === 'active' ? `
                            <button class="action-btn" onclick="editWarning(${w.id})"><i class="fas fa-edit"></i><span class="tooltip">Modify</span></button>
                            <button class="action-btn" onclick="extendWarning(${w.id})"><i class="fas fa-clock"></i><span class="tooltip">Extend</span></button>
                            <button class="action-btn success" onclick="resolveWarning(${w.id})"><i class="fas fa-check"></i><span class="tooltip">Resolve</span></button>
                            <button class="action-btn danger" onclick="removeWarning(${w.id})"><i class="fas fa-trash"></i><span class="tooltip">Remove</span></button>
                        ` : ''}
                        ${w.status === 'pending' ? `
                            <button class="action-btn success" onclick="resolveWarning(${w.id})"><i class="fas fa-check"></i><span class="tooltip">Resolve</span></button>
                            <button class="action-btn danger" onclick="removeWarning(${w.id})"><i class="fas fa-trash"></i><span class="tooltip">Remove</span></button>
                        ` : ''}
                        ${w.status === 'resolved' ? `<span style="color:#22c55e;"><i class="fas fa-check-circle"></i> Resolved</span>` : ''}
                    </td>
                </tr>
            `).join('');
            document.getElementById('warningCount').textContent = warnings.length;

            const appealsContainer = document.getElementById('appealsContainer');
            if (appeals.length === 0) {
                appealsContainer.innerHTML = '<div style="padding:10px; color:#64748b;">No pending appeals.</div>';
            } else {
                appealsContainer.innerHTML = appeals.map(a => `
                    <div class="appeal-box">
                        <div><strong>${a.user}</strong> - Appeal submitted for "${a.warning}" warning</div>
                        <div class="appeal-meta">Submitted: ${a.submitted} · 
                            <button class="action-btn success" onclick="approveAppeal(${a.id})"><i class="fas fa-check"></i><span class="tooltip">Approve</span></button> 
                            <button class="action-btn danger" onclick="dismissAppeal(${a.id})"><i class="fas fa-times"></i><span class="tooltip">Dismiss</span></button>
                        </div>
                    </div>
                `).join('');
            }
        }

        function updateKPIs() {
            const total = users.length;
            const active = users.filter(u => u.status === 'active').length;
            const inactive = users.filter(u => u.status === 'inactive').length;
            const blocked = users.filter(u => u.status === 'blocked').length;
            document.getElementById('kpiTotalUsers').textContent = total;
            document.getElementById('kpiActiveUsers').textContent = active;
            document.getElementById('kpiInactiveUsers').textContent = inactive;
            document.getElementById('kpiBlockedUsers').textContent = blocked;
            document.getElementById('userCount').textContent = total;
            updateInactiveFeed();
        }

        function updateInactiveFeed() {
            const inactiveUsers = users.filter(u => u.status === 'inactive' || u.status === 'blocked');
            const feed = document.getElementById('inactiveFeed');
            if (inactiveUsers.length === 0) {
                feed.innerHTML = '<div style="padding:10px; color:#64748b;">No inactive users at this time.</div>';
                document.getElementById('atRiskCount').textContent = '0';
                return;
            }
            feed.innerHTML = inactiveUsers.map(u => `
                <div class="feed-item" onclick="viewUser(${u.id})">
                    <div><strong>${u.name}</strong> <span class="status-badge ${u.status}">${u.status.charAt(0).toUpperCase() + u.status.slice(1)}</span></div>
                    <div>
                        <span style="color:#64748b; font-size:12px;">Last seen: ${u.lastSeen || 'N/A'}</span>
                        <button class="action-btn warning" onclick="event.stopPropagation(); notifyUser(${u.id})"><i class="fas fa-envelope"></i><span class="tooltip">Send Notification</span></button>
                    </div>
                </div>
            `).join('');
            document.getElementById('atRiskCount').textContent = inactiveUsers.length;
        }

        function getColor(name) {
            const colors = ['#2563eb', '#7c3aed', '#16a34a', '#dc2626', '#f59e0b', '#0891b2', '#8b5cf6', '#ec4899'];
            return colors[name.length % colors.length];
        }

        function updateBadges() {
            document.getElementById('userCount').textContent = users.length;
            document.getElementById('groupCount').textContent = groups.length;
            document.getElementById('topicCount').textContent = topics.length;
            document.getElementById('warningCount').textContent = warnings.length;
        }

        // ===== USER ACTIONS =====
        function viewUser(id) {
            const u = users.find(u => u.id === id);
            if (!u) return;
            const modal = document.getElementById('userDetailModal');
            const body = document.getElementById('userDetailBody');
            const verificationText = u.role === 'lecturer'
                ? (u.verification_status === 'approved' ? 'Verified' : (u.verification_status === 'rejected' ? 'Rejected' : 'Pending'))
                : 'Not required';
            body.innerHTML = `
                <div class="detail-row"><span class="label">Full Name</span><span class="value">${u.name}</span></div>
                <div class="detail-row"><span class="label">Email</span><span class="value">${u.email}</span></div>
                <div class="detail-row"><span class="label">Role</span><span class="value"><span class="role-badge role-${u.role}">${u.role.charAt(0).toUpperCase() + u.role.slice(1)}</span></span></div>
                <div class="detail-row"><span class="label">Status</span><span class="value"><span class="status-badge ${u.status}">${u.status.charAt(0).toUpperCase() + u.status.slice(1)}</span></span></div>
                <div class="detail-row"><span class="label">Verification</span><span class="value">${verificationText}</span></div>
                <div class="detail-row"><span class="label">Last Seen</span><span class="value">${u.lastSeen || 'N/A'}</span></div>
                <div class="detail-row"><span class="label">User ID</span><span class="value">#${String(u.id).padStart(4, '0')}</span></div>
            `;
            modal.classList.add('show');
        }

        function verifyLecturer(id) {
            const u = users.find(u => u.id === id);
            if (u) {
                u.verification_status = 'approved';
                u.status = 'active';
                u.verified = true;
                renderUsers();
                updateKPIs();
                alert(`✅ Lecturer ${u.name} has been verified.`);
            }
        }

        function rejectLecturer(id) {
            if (confirm('Reject this lecturer registration?')) {
                const u = users.find(u => u.id === id);
                if (u) {
                    u.verification_status = 'rejected';
                    u.status = 'blocked';
                    renderUsers();
                    updateKPIs();
                    alert(`❌ Lecturer ${u.name} registration has been rejected.`);
                }
            }
        }

        function blockUser(id) {
            const u = users.find(u => u.id === id);
            if (u) {
                if (!confirm(`Are you sure you want to block ${u.name}?`)) return;
                u.status = 'blocked';
                renderUsers();
                updateKPIs();
                alert(`🛑 ${u.name} has been blocked.`);
            }
        }

        function unblockUser(id) {
            const u = users.find(u => u.id === id);
            if (u) {
                u.status = 'active';
                renderUsers();
                updateKPIs();
                alert(`✅ ${u.name} has been unblocked.`);
            }
        }

        function notifyUser(id) {
            const u = users.find(u => u.id === id);
            if (u) alert(`📧 Notification sent to ${u.name} (${u.email})`);
        }

        // ===== GROUP ACTIONS =====
        function viewGroup(id) {
            const g = groups.find(g => g.id === id);
            if (g) {
                const members = users.filter(u => u.status === 'active');
                alert(`📊 Group: ${g.name}\nDescription: ${g.description}\nMembers: ${g.members}\nPosts/Week: ${g.posts}\nStatus: ${g.status}\n\nActive Users: ${members.length}`);
            }
        }

        function warnGroup(id) {
            const g = groups.find(g => g.id === id);
            if (g) {
                g.status = 'warned';
                renderGroups();
                alert(`⚠️ Group "${g.name}" has been warned. Block only if the issue persists.`);
            }
        }

        function blockGroup(id) {
            const g = groups.find(g => g.id === id);
            if (g) {
                if (!confirm(`Are you sure you want to block ${g.name}?`)) return;
                g.status = 'blocked';
                renderGroups();
                alert(`🛑 Group "${g.name}" has been blocked.`);
            }
        }

        function toggleGroupStatus(id) {
            const g = groups.find(g => g.id === id);
            if (g) {
                g.status = g.status === 'blocked' ? 'active' : 'blocked';
                renderGroups();
            }
        }

        // ===== TOPIC ACTIONS =====
        function viewTopic(id) {
            const t = topics.find(t => t.id === id);
            if (t) {
                alert(`📝 Topic: ${t.name}\nCategory: ${t.category}\nAuthor: ${t.author}\nReplies: ${t.replies}\nViews: ${t.views}\nEngagement: ${t.engagement}%\nStatus: ${t.status}`);
            }
        }

        function flagTopic(id) {
            const t = topics.find(t => t.id === id);
            if (t) { 
                t.status = 'flagged'; 
                renderTopics(); 
                alert(`🚩 Topic "${t.name}" has been flagged for review.`);
            }
        }

        function approveTopic(id) {
            const t = topics.find(t => t.id === id);
            if (t) { 
                t.status = 'active'; 
                renderTopics(); 
                alert(`✅ Topic "${t.name}" has been approved.`);
            }
        }

        function deleteTopic(id) {
            if (confirm('Delete this topic?')) {
                topics = topics.filter(t => t.id !== id);
                renderTopics();
                updateBadges();
                alert('🗑️ Topic deleted successfully.');
            }
        }

        function bulkDeleteTopics() {
            const selected = document.querySelectorAll('.topic-select:checked');
            
            if (selected.length === 0) {
                alert('Please select at least one topic to delete.');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${selected.length} topic(s)? This cannot be undone!`)) {
                const idsToDelete = Array.from(selected).map(cb => parseInt(cb.dataset.id));
                topics = topics.filter(t => !idsToDelete.includes(t.id));
                renderTopics();
                updateBadges();
                alert(`✅ ${idsToDelete.length} topic(s) deleted successfully!`);
            }
        }

        // ===== WARNING ACTIONS =====
        function resolveWarning(id) {
            const w = warnings.find(w => w.id === id);
            if (w) { 
                w.status = 'resolved'; 
                renderWarnings(); 
                alert('✅ Warning resolved.');
            }
        }

        function extendWarning(id) {
            const w = warnings.find(w => w.id === id);
            if (w) {
                const exp = new Date(w.expires);
                exp.setDate(exp.getDate() + 14);
                w.expires = exp.toISOString().split('T')[0];
                renderWarnings();
                alert('⏰ Warning extended by 14 days.');
            }
        }

        function editWarning(id) {
            const w = warnings.find(w => w.id === id);
            if (w) openModal('warning', w);
        }

        function removeWarning(id) {
            if (confirm('Remove this warning?')) {
                warnings = warnings.filter(w => w.id !== id);
                renderWarnings();
                updateBadges();
                alert('🗑️ Warning removed.');
            }
        }

        // ===== APPEAL ACTIONS =====
        function approveAppeal(id) {
            appeals = appeals.filter(a => a.id !== id);
            renderWarnings();
            alert('✅ Appeal approved. Warning removed.');
        }

        function dismissAppeal(id) {
            appeals = appeals.filter(a => a.id !== id);
            renderWarnings();
            alert('❌ Appeal dismissed. Warning remains.');
        }

        // ===== SETTINGS =====
        function applyThemeColor() {
            const color = document.getElementById('themeColor').value;
            document.getElementById('colorPreview').style.background = color;
            document.querySelectorAll('.btn-primary').forEach(b => {
                b.style.background = color;
                b.style.borderColor = color;
            });
            document.querySelectorAll('.nav-item.active i').forEach(i => {
                i.style.color = color;
            });
        }

        function saveSettings() {
            alert('⚙️ Settings saved successfully!');
        }

        // ===== MODAL FUNCTIONS =====
        function openModal(type, data) {
            const modal = document.getElementById('modal');
            const title = document.getElementById('modalTitle');
            const body = document.getElementById('modalBody');

            const types = {
                warning: {
                    title: data ? 'Modify Warning' : 'Issue Warning',
                    fields: `
                        <label>User</label>
                        <select id="mUser">
                            ${users.filter(u => u.status === 'active' || u.status === 'warned').map(u => 
                                `<option value="${u.name}" ${data?.user === u.name ? 'selected' : ''}>${u.name}</option>`
                            ).join('')}
                        </select>
                        <label>Reason</label>
                        <select id="mReason">
                            <option value="Spam" ${data?.reason === 'Spam' ? 'selected' : ''}>Spam</option>
                            <option value="Harassment" ${data?.reason === 'Harassment' ? 'selected' : ''}>Harassment</option>
                            <option value="Inactivity" ${data?.reason === 'Inactivity' ? 'selected' : ''}>Inactivity</option>
                            <option value="Academic misconduct" ${data?.reason === 'Academic misconduct' ? 'selected' : ''}>Academic misconduct</option>
                            <option value="Policy violation" ${data?.reason === 'Policy violation' ? 'selected' : ''}>Policy violation</option>
                        </select>
                        <label>Warning #</label>
                        <input id="mNumber" type="number" value="${data?.number || 1}" min="1" max="3">
                        <small style="color:#64748b;">Maximum 3 warnings per user.</small>
                    `
                }
            };

            const t = types[type];
            if (!t) return;
            title.textContent = t.title;
            body.innerHTML = t.fields;
            modal.dataset.type = type;
            modal.dataset.id = data?.id || '';
            modal.classList.add('show');
        }

        function closeModal() {
            document.getElementById('modal').classList.remove('show');
        }

        function saveModal() {
            const type = document.getElementById('modal').dataset.type;
            const id = document.getElementById('modal').dataset.id;
            const user = document.getElementById('mUser')?.value;
            const reason = document.getElementById('mReason')?.value;
            const number = parseInt(document.getElementById('mNumber')?.value) || 1;

            if (type === 'warning') {
                // Check if user already has 3 warnings
                const userWarnings = warnings.filter(w => w.user === user);
                if (userWarnings.length >= 3) {
                    alert('⚠️ This user already has 3 warnings. Maximum limit reached!');
                    closeModal();
                    return;
                }

                if (id) {
                    const w = warnings.find(w => w.id == id);
                    if (w) { w.user = user; w.reason = reason; w.number = number; }
                } else {
                    const date = new Date();
                    const exp = new Date();
                    exp.setDate(exp.getDate() + 30);
                    warnings.push({ 
                        id: nextIds.warning++, 
                        user, 
                        reason, 
                        number, 
                        issued: date.toISOString().split('T')[0], 
                        expires: exp.toISOString().split('T')[0], 
                        status: 'active' 
                    });
                }
                renderWarnings();
                updateBadges();
                alert('✅ Warning issued successfully!');
            }
            closeModal();
        }

        // ===== CHART EXPANSION =====
        function expandChart(type) {
            const modal = document.getElementById('chartModal');
            document.getElementById('chartModalTitle').textContent = 
                type === 'activity' ? 'User Activity (Last 7 Days)' :
                type === 'topics' ? 'Top Discussion Analytics' : 'User Status Distribution';
            
            const canvas = document.getElementById('expandedChart');
            const ctx = canvas.getContext('2d');
            
            if (window.expandedChartInstance) {
                window.expandedChartInstance.destroy();
            }

            let config;
            if (type === 'activity') {
                config = {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Posts & Replies',
                            data: [120, 180, 150, 220, 280, 200, 160],
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37,99,235,0.1)',
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#2563eb',
                            borderWidth: 3,
                            pointRadius: 5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'top' },
                            tooltip: { callbacks: { label: function(context) { return `${context.parsed.y} activities`; } } }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#eef2f6' } },
                            x: { grid: { display: false } }
                        }
                    }
                };
            } else if (type === 'topics') {
                config = {
                    type: 'bar',
                    data: {
                        labels: ['Data Analysis', 'Tech Analysis', 'Data Mgmt', 'Comm Skills', 'AI Ethics'],
                        datasets: [{
                            label: 'Engagement Score',
                            data: [89, 67, 112, 54, 72],
                            backgroundColor: ['#3b82f6', '#60a5fa', '#93bbfc', '#b9d3ff', '#2563eb'],
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: function(context) { return `${context.parsed.x}% engagement`; } } }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#eef2f6' } },
                            x: { grid: { display: false } }
                        }
                    }
                };
            } else {
                const active = users.filter(u => u.status === 'active').length;
                const inactive = users.filter(u => u.status === 'inactive').length;
                const warned = users.filter(u => u.status === 'warned').length;
                const blocked = users.filter(u => u.status === 'blocked').length;
                config = {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Inactive', 'Warned', 'Blocked'],
                        datasets: [{
                            data: [active, inactive, warned, blocked],
                            backgroundColor: ['#22c55e', '#94a3b8', '#facc15', '#ef4444'],
                            borderColor: 'white',
                            borderWidth: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 12 } } },
                            tooltip: { callbacks: { label: function(context) { 
                                const total = context.dataset.data.reduce((a,b) => a+b, 0);
                                return `${context.label}: ${context.parsed} (${Math.round((context.parsed/total)*100)}%)`;
                            } } }
                        },
                        cutout: '65%',
                    }
                };
            }

            window.expandedChartInstance = new Chart(ctx, config);
            modal.classList.add('show');
        }

        // ===== NAVIGATION =====
        function navigateTo(page) {
            document.querySelectorAll('.page-panel').forEach(p => p.classList.remove('active'));
            const target = document.getElementById('page-' + page);
            if (target) target.classList.add('active');

            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            const navItem = document.querySelector(`.nav-item[data-page="${page}"]`);
            if (navItem) navItem.classList.add('active');

            const titles = {
                overview: 'Dashboard Overview',
                users: 'User Management',
                groups: 'Group Management',
                topics: 'Topic Moderation',
                warnings: 'Warning Management',
                settings: 'Platform Settings',
                logout: 'Log Out'
            };
            const title = titles[page] || 'Dashboard';
            document.getElementById('pageTitle').innerHTML = `<i class="fas fa-${page === 'overview' ? 'gauge-high' : 'circle'}" style="color:#2563eb; margin-right:10px;"></i>${title}`;
        }

        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                const page = this.dataset.page;
                if (page) navigateTo(page);
            });
        });

        // ===== CHARTS =====
        (function initCharts() {
            const ctx1 = document.getElementById('activityChart')?.getContext('2d');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Posts & Replies',
                            data: [120, 180, 150, 220, 280, 200, 160],
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37,99,235,0.06)',
                            fill: true,
                            tension: 0.25,
                            pointBackgroundColor: '#2563eb',
                            borderWidth: 2.5,
                            pointRadius: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#eef2f6' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            const ctx2 = document.getElementById('topicsChart')?.getContext('2d');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['Data Analysis', 'Tech Analysis', 'Data Mgmt', 'Comm Skills', 'AI Ethics'],
                        datasets: [{
                            label: 'Engagement',
                            data: [89, 67, 112, 54, 72],
                            backgroundColor: ['#3b82f6', '#60a5fa', '#93bbfc', '#b9d3ff', '#2563eb'],
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { beginAtZero: true, grid: { color: '#eef2f6' } },
                            y: { grid: { display: false } }
                        }
                    }
                });
            }

            const ctx3 = document.getElementById('distChart')?.getContext('2d');
            if (ctx3) {
                const active = users.filter(u => u.status === 'active').length;
                const inactive = users.filter(u => u.status === 'inactive').length;
                const warned = users.filter(u => u.status === 'warned').length;
                const blocked = users.filter(u => u.status === 'blocked').length;
                new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Inactive', 'Warned', 'Blocked'],
                        datasets: [{
                            data: [active, inactive, warned, blocked],
                            backgroundColor: ['#22c55e', '#94a3b8', '#facc15', '#ef4444'],
                            borderColor: 'white',
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                        },
                        cutout: '70%',
                    }
                });
            }
        })();

        // ===== INITIAL RENDER =====
        renderUsers();
        renderGroups();
        renderTopics();
        renderWarnings();
        updateKPIs();
        updateBadges();

        // Close modals on outside click
        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.getElementById('userDetailModal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('show');
        });
        document.getElementById('chartModal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('show');
        });

        console.log('✅ Smart Discussion Forum Admin System initialized');
    </script>
</body>
</html>