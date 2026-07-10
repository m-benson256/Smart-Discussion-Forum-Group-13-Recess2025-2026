<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Institutional Forum Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    

</head>

<body>


    <!-- Sidebar -->

    <aside class="sidebar">

        <div class="sidebar-brand">

            <i class="fas fa-university"></i>

            <span>Smart Discussion Forum</span>

        </div>

        <button class="nav-item active" data-page="overview">

            <i class="fas fa-chart-pie"></i>

            <span>Dashboard</span>

        </button>

        <button class="nav-item" data-page="users">

            <i class="fas fa-users"></i>

            <span>Users</span>

            <span class="badge-nav" id="userCount">10</span>

        </button>

        <button class="nav-item" data-page="groups">

            <i class="fas fa-layer-group"></i>

            <span>Groups</span>

            <span class="badge-nav" id="groupCount">6</span>

        </button>

        <button class="nav-item" data-page="topics">

            <i class="fas fa-tags"></i>

            <span>Topics</span>

            <span class="badge-nav" id="topicCount">8</span>

        </button>

        <button class="nav-item" data-page="warnings">

            <i class="fas fa-exclamation-triangle"></i>

            <span>Warnings</span>

            <span class="badge-nav" id="warningCount">5</span>

        </button>

        <button class="nav-item" data-page="reports">

            <i class="fas fa-flag"></i>

            <span>Reports</span>

            <span class="badge-nav" id="reportCount">4</span>

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

                <div class="kpi-number" id="kpiTotalUsers">{{ $totalUsers }}</div>

                    <div class="kpi-sub"><i class="fas fa-arrow-up" style="color:#16a34a;"></i> +2 this month</div>

                </div>

                <div class="kpi-card" onclick="navigateTo('users')">

                    <div class="kpi-icon"><i class="fas fa-user-check"></i></div>

                    <div class="kpi-label">Active Users</div>

                    <div class="kpi-number" id="kpiActiveUsers">{{ $activeUsers }}</div>

                    <div class="kpi-sub">60% of total</div>

                </div>

                <div class="kpi-card" onclick="navigateTo('users')">

                     <div class="kpi-icon"><i class="fas fa-user-clock"></i></div>

                    <div class="kpi-label">Inactive Users</div>

                    <div class="kpi-number" id="kpiInactiveUsers">{{ $inactiveUsers }}</div>

                    <div class="kpi-sub">Last 30 days</div>

                </div>

                <div class="kpi-card" onclick="navigateTo('users')">

                    <div class="kpi-icon"><i class="fas fa-ban"></i></div>

                    <div class="kpi-label">Blocked Users</div>

            <div class="kpi-number" id="kpiblockedUsers">{{$blockedUsers }}</div>

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

                    <h3><i class="fas fa-clock" style="color: #f59e0b;"></i> Recent Inactive Users (Approaching Penalty)</h3>

                    <span class="badge-action"><i class="fas fa-exclamation-triangle"></i> <span id="atRiskCount">2</span> at risk</span>

                </div>

                <div id="inactiveFeed"></div>

            </div>

        </div>



        <!-- ===== USERS ===== -->

        <div class="page-panel" id="page-users">

            <div class="placeholder-content">

                <h2><i class="fas fa-users" style="color:#2563eb;"></i> User Management</h2>

                <p>Manage students and lecturers. Click on a user to view details.</p>

                <div class="actions">

                    <button class="btn btn-success" onclick="openModal('user')"><i class="fas fa-user-plus"></i> Add User</button>

                    <button class="btn"><i class="fas fa-file-export"></i> Export</button>

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

                            <tr><th>#</th><th>Full Name</th><th>Email</th><th>Role</th><th>Status</th><th>Verified</th><th>Actions</th></tr>

                        </thead>

                        <tbody id="userTableBody"></tbody>

                    </table>

                </div>

                <div style="margin-top:12px; font-size:13px; color:#64748b;">Showing <span id="userShowCount">10</span> users</div>

            </div>

        </div>



        <!-- ===== GROUPS ===== -->

        <div class="page-panel" id="page-groups">

            <div class="placeholder-content">

                <h2><i class="fas fa-layer-group" style="color:#2563eb;"></i> Group Management</h2>

                <p>Create course groups, departmental forums, and manage memberships & permissions.</p>

                <div class="actions">

                    <button class="btn btn-success" onclick="openModal('group')"><i class="fas fa-plus"></i> Create Group</button>

                    <button class="btn"><i class="fas fa-sync"></i> Refresh</button>

                </div>

                <div class="table-wrap">

                    <table>

                        <thead>

                            <tr><th>Group Name</th><th>Description</th><th>Members</th><th>Type</th><th>Posts/Week</th><th>Status</th><th>Actions</th></tr>

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

                <p>Moderate academic subjects, announcements, and general discussions.</p>

                <div class="actions">

                    <button class="btn btn-success" onclick="openModal('topic')"><i class="fas fa-plus"></i> Add Topic</button>

                    <button class="btn" onclick="groupTopics()"><i class="fas fa-layer-group"></i> Group by Category</button>

                    <button class="btn btn-danger"><i class="fas fa-trash"></i> Bulk Delete</button>

                </div>



                <div id="topicsContainer">

                    <h4 style="margin:16px 0 10px;">Active Topics</h4>

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

                <p>Track issued warnings, manage escalation rules, and handle appeals.</p>

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



        <!-- ===== REPORTS ===== -->

        <div class="page-panel" id="page-reports">

            <div class="placeholder-content">

                <h2><i class="fas fa-flag" style="color:#2563eb;"></i> Reports & Moderation</h2>

                <p>View user-submitted reports, manage report queue, and track trends.</p>

                <div class="actions">

                    <button class="btn btn-primary"><i class="fas fa-sync"></i> Refresh Queue</button>

                    <button class="btn"><i class="fas fa-file-pdf"></i> Export Report</button>

                </div>



                <div class="table-wrap">

                    <table>

                        <thead>

                            <tr><th>Report ID</th><th>Reported By</th><th>Target</th><th>Reason</th><th>Submitted</th><th>Status</th><th>Actions</th></tr>

                        </thead>

                        <tbody id="reportTableBody"></tbody>

                    </table>

                </div>



                <div class="stats-grid" style="margin-top:16px;" id="reportStats">

                    <div class="stat-card" onclick="filterReports('all')">

                        <div class="stat-number" style="color:#2563eb;" id="totalReports">4</div>

                        <div class="stat-label">Total Reports</div>

                    </div>

                    <div class="stat-card" onclick="filterReports('pending')">

                        <div class="stat-number" style="color:#f59e0b;" id="pendingReports">2</div>

                        <div class="stat-label">Pending Review</div>

                    </div>

                    <div class="stat-card" onclick="filterReports('resolved')">

                        <div class="stat-number" style="color:#22c55e;" id="resolutionRate">75%</div>

                        <div class="stat-label">Resolution Rate</div>

                    </div>

                </div>



                <div style="margin-top:12px; background:#f8fafc; border-radius:12px; padding:14px;">

                    <strong>Frequent Issues:</strong> Spam (34%), Harassment (22%), Inappropriate content (18%), Academic misconduct (16%), Other (10%)

                </div>

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

                        <input type="text" value="Institutional Forum Hub" id="siteName">

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
 let users = [ 

            { id: 1, name: 'John Doe', email: 'john@students.ed', role: 'student', status: 'active', verified: true, lastSeen: '2026-06-29 14:32' },

            { id: 2, name: 'Jane Smith', email: 'jane@lecturers.ed', role: 'lecturer', status: 'active', verified: true, lastSeen: '2026-06-30 09:15' },

            { id: 3, name: 'Mike Roberts', email: 'mike@students.ed', role: 'student', status: 'blocked', verified: false, lastSeen: '2026-06-05 08:30' },

            { id: 4, name: 'Chris Brown', email: 'chris@lecturers.ed', role: 'lecturer', status: 'inactive', verified: false, lastSeen: '2026-06-26 11:10' },

            { id: 5, name: 'Emily Davis', email: 'emily@students.ed', role: 'student', status: 'warned', verified: true, lastSeen: '2026-06-27 13:10' },

            { id: 6, name: 'Amanda Lee', email: 'amanda@students.ed', role: 'student', status: 'active', verified: false, lastSeen: '2026-06-29 18:22' },

            { id: 7, name: 'Sarah Wilson', email: 'sarah@lecturers.ed', role: 'lecturer', status: 'inactive', verified: false, lastSeen: '2026-06-28 16:45' },

            { id: 8, name: 'Linda Chen', email: 'linda@students.ed', role: 'student', status: 'blocked', verified: true, lastSeen: '2026-06-04 22:00' }

        ];



        let groups = [

            { id: 1, name: 'CS 2024', description: 'Computer Science department', members: 45, type: 'public', posts: 128, status: 'active' },

            { id: 2, name: 'Faculty Lounge', description: 'Staff and lecturers', members: 12, type: 'private', posts: 34, status: 'active' },

            { id: 3, name: 'Math Study Group', description: 'Advanced mathematics', members: 28, type: 'public', posts: 67, status: 'warned' },

            { id: 4, name: 'Research Hub', description: 'Research collaboration', members: 8, type: 'private', posts: 19, status: 'blocked' },

            { id: 5, name: 'Physics Forum', description: 'Physics discussions', members: 15, type: 'public', posts: 45, status: 'active' },

            { id: 6, name: 'Admin Board', description: 'Administrative announcements', members: 5, type: 'private', posts: 12, status: 'active' }

        ];



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



        let warnings = [

            { id: 1, user: 'Mike Wilson', number: 1, reason: 'Spam', issued: '2026-06-20', expires: '2026-07-20', status: 'active' },

            { id: 2, user: 'Sarah Lee', number: 2, reason: 'Harassment', issued: '2026-06-15', expires: '2026-07-15', status: 'active' },

            { id: 3, user: 'Tom Brown', number: 1, reason: 'Inactivity', issued: '2026-06-01', expires: '2026-06-30', status: 'resolved' },

            { id: 4, user: 'Emily Davis', number: 1, reason: 'Academic misconduct', issued: '2026-06-25', expires: '2026-07-25', status: 'pending' },

            { id: 5, user: 'Linda Chen', number: 3, reason: 'Policy violation', issued: '2026-06-18', expires: '2026-07-18', status: 'active' }

        ];



        let reports = [

            { id: 'R-001', reportedBy: 'Jane Smith', target: 'Anonymous User', reason: 'Spam', submitted: '2026-06-22', status: 'pending' },

            { id: 'R-002', reportedBy: 'John Doe', target: 'UserX', reason: 'Harassment', submitted: '2026-06-20', status: 'pending' },

            { id: 'R-003', reportedBy: 'Sarah Lee', target: 'Mike Roberts', reason: 'Inappropriate content', submitted: '2026-06-18', status: 'resolved' },

            { id: 'R-004', reportedBy: 'Anonymous', target: 'Group: Research Hub', reason: 'Policy violation', submitted: '2026-06-25', status: 'dismissed' }

        ];



        let appeals = [

            { id: 1, user: 'Emily Davis', warning: 'Academic misconduct', submitted: '2026-06-26' },

            { id: 2, user: 'Linda Chen', warning: 'Policy violation', submitted: '2026-06-27' }

        ];



        let nextIds = { user: 11, group: 7, topic: 9, warning: 6, report: 5, appeal: 3 };

        let currentReportFilter = 'all';



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

            tbody.innerHTML = filtered.map((u, index) => `

                <tr onclick="showUserDetail(${u.id})" style="cursor:pointer;">

                    <td>${index + 1}</td>

                    <td><div class="user-cell"><span class="user-avatar" style="background:${getColor(u.name)};">${u.name.split(' ').map(n => n[0]).join('')}</span> ${u.name}</div></td>

                    <td>${u.email}</td>

                    <td><span class="role-badge role-${u.role}">${u.role.charAt(0).toUpperCase() + u.role.slice(1)}</span></td>

                    <td><span class="status-badge ${u.status}">${u.status.charAt(0).toUpperCase() + u.status.slice(1)}</span></td>

                    <td><span class="status-badge ${u.verified ? 'verified' : 'unverified'}"><i class="fas ${u.verified ? 'fa-check' : 'fa-times'}"></i> ${u.verified ? 'Verified' : 'Unverified'}</span></td>

                    <td onclick="event.stopPropagation();">

                        <button class="action-btn" onclick="editUser(${u.id})"><i class="fas fa-edit"></i><span class="tooltip">Edit User</span></button>

                        <button class="action-btn ${u.status === 'blocked' ? 'success' : 'danger'}" onclick="toggleUserStatus(${u.id})">

                            <i class="fas ${u.status === 'blocked' ? 'fa-unlock' : 'fa-ban'}"></i>

                            <span class="tooltip">${u.status === 'blocked' ? 'Unblock' : 'Block'}</span>

                        </button>

                        ${!u.verified ? `<button class="action-btn success" onclick="verifyUser(${u.id})"><i class="fas fa-check"></i><span class="tooltip">Verify</span></button>` : ''}

                        <button class="action-btn danger" onclick="deleteUser(${u.id})"><i class="fas fa-trash"></i><span class="tooltip">Delete</span></button>

                    </td>

                </tr>

            `).join('');

            document.getElementById('userShowCount').textContent = filtered.length;

            document.getElementById('userCount').textContent = users.length;

            updateKPIs();

        }



        function filterUsers() { renderUsers(); }



        function renderGroups() {

            const tbody = document.getElementById('groupTableBody');

            tbody.innerHTML = groups.map(g => `

                <tr>

                    <td><strong>${g.name}</strong></td>

                    <td>${g.description}</td>

                    <td>${g.members}</td>

                    <td><span class="status-badge ${g.type}">${g.type.charAt(0).toUpperCase() + g.type.slice(1)}</span></td>

                    <td>${g.posts}</td>

                    <td><span class="status-badge ${g.status}">${g.status.charAt(0).toUpperCase() + g.status.slice(1)}</span></td>

                    <td>

                        <button class="action-btn" onclick="editGroup(${g.id})"><i class="fas fa-edit"></i><span class="tooltip">Edit Group</span></button>

                        <button class="action-btn ${g.status === 'blocked' ? 'success' : 'danger'}" onclick="toggleGroupStatus(${g.id})">

                            <i class="fas ${g.status === 'blocked' ? 'fa-unlock' : 'fa-ban'}"></i>

                            <span class="tooltip">${g.status === 'blocked' ? 'Unblock' : 'Block'}</span>

                        </button>

                        <button class="action-btn danger" onclick="deleteGroup(${g.id})"><i class="fas fa-trash"></i><span class="tooltip">Delete</span></button>

                    </td>

                </tr>

            `).join('');

            document.getElementById('groupCount').textContent = groups.length;

        }



        let groupedView = false;



        function renderTopics() {

            const grid = document.getElementById('topicsGrid');

            let activeTopics = topics.filter(t => t.status === 'active');

            

            if (groupedView) {

                const categories = {};

                activeTopics.forEach(t => {

                    if (!categories[t.category]) categories[t.category] = [];

                    categories[t.category].push(t);

                });

                let html = '';

                Object.keys(categories).forEach(cat => {

                    html += `<div style="grid-column:1/-1; margin-top:8px;"><strong style="color:#475569;">${cat}</strong></div>`;

                    categories[cat].forEach(t => {

                        html += `

                            <div class="topic-card">

                                <div class="topic-info">

                                    <h4>${t.name} <span class="status-badge" style="background:#dbeafe;color:#1e40af;font-size:10px;">${t.category}</span></h4>

                                    <p>${t.replies} replies · ${t.views} views · ${t.engagement}% engagement</p>

                                </div>

                                <div>

                                    <button class="action-btn" onclick="togglePinTopic(${t.id})"><i class="fas fa-thumbtack"></i><span class="tooltip">Pin/Unpin</span></button>

                                    <button class="action-btn warning" onclick="flagTopic(${t.id})"><i class="fas fa-flag"></i><span class="tooltip">Flag</span></button>

                                    <button class="action-btn" onclick="editTopic(${t.id})"><i class="fas fa-edit"></i><span class="tooltip">Edit</span></button>

                                    <button class="action-btn danger" onclick="deleteTopic(${t.id})"><i class="fas fa-trash"></i><span class="tooltip">Delete</span></button>

                                </div>

                            </div>

                        `;

                    });

                });

                grid.innerHTML = html;

            } else {

                grid.innerHTML = activeTopics.map(t => `

                    <div class="topic-card">

                        <div class="topic-info">

                            <h4>${t.name} <span class="status-badge" style="background:#dbeafe;color:#1e40af;font-size:10px;">${t.category}</span></h4>

                            <p>${t.replies} replies · ${t.views} views · ${t.engagement}% engagement</p>

                        </div>

                        <div>

                            <button class="action-btn" onclick="togglePinTopic(${t.id})"><i class="fas fa-thumbtack"></i><span class="tooltip">Pin/Unpin</span></button>

                            <button class="action-btn warning" onclick="flagTopic(${t.id})"><i class="fas fa-flag"></i><span class="tooltip">Flag</span></button>

                            <button class="action-btn" onclick="editTopic(${t.id})"><i class="fas fa-edit"></i><span class="tooltip">Edit</span></button>

                            <button class="action-btn danger" onclick="deleteTopic(${t.id})"><i class="fas fa-trash"></i><span class="tooltip">Delete</span></button>

                        </div>

                    </div>

                `).join('');

            }



            const pending = topics.filter(t => t.status === 'flagged' || t.status === 'pending');

            const pendingBody = document.getElementById('pendingTopicsBody');

            pendingBody.innerHTML = pending.map(t => `

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

            document.getElementById('topicCount').textContent = topics.length;

        }



        function groupTopics() {

            groupedView = !groupedView;

            renderTopics();

            document.querySelector('#page-topics .btn[onclick="groupTopics()"]').textContent = groupedView ? 'Ungroup' : 'Group by Category';

        }



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



        function renderReports() {

            let filtered = reports;

            if (currentReportFilter === 'pending') filtered = reports.filter(r => r.status === 'pending');

            else if (currentReportFilter === 'resolved') filtered = reports.filter(r => r.status === 'resolved');

            else if (currentReportFilter === 'dismissed') filtered = reports.filter(r => r.status === 'dismissed');



            const tbody = document.getElementById('reportTableBody');

            tbody.innerHTML = filtered.map(r => `

                <tr>

                    <td>${r.id}</td>

                    <td>${r.reportedBy}</td>

                    <td>${r.target}</td>

                    <td>${r.reason}</td>

                    <td>${r.submitted}</td>

                    <td><span class="status-badge ${r.status}">${r.status.charAt(0).toUpperCase() + r.status.slice(1)}</span></td>

                    <td>

                        ${r.status === 'pending' ? `

                            <button class="action-btn success" onclick="resolveReport('${r.id}')"><i class="fas fa-check"></i><span class="tooltip">Resolve</span></button>

                            <button class="action-btn" onclick="dismissReport('${r.id}')"><i class="fas fa-times"></i><span class="tooltip">Dismiss</span></button>

                        ` : `<span style="color:#64748b;">${r.status === 'resolved' ? '✅ Resolved' : '🚫 Dismissed'}</span>`}

                    </td>

                </tr>

            `).join('');

            document.getElementById('reportCount').textContent = reports.length;

            document.getElementById('totalReports').textContent = reports.length;

            document.getElementById('pendingReports').textContent = reports.filter(r => r.status === 'pending').length;

            const resolved = reports.filter(r => r.status === 'resolved').length;

            document.getElementById('resolutionRate').textContent = reports.length > 0 ? Math.round((resolved / reports.length) * 100) + '%' : '0%';

        }



        function filterReports(status) {

            currentReportFilter = status;

            renderReports();

            document.querySelectorAll('.stat-card').forEach(c => c.style.border = 'none');

            if (status === 'all') document.querySelector('.stat-card:first-child').style.border = '2px solid #2563eb';

            else if (status === 'pending') document.querySelector('.stat-card:nth-child(2)').style.border = '2px solid #f59e0b';

            else if (status === 'resolved') document.querySelector('.stat-card:last-child').style.border = '2px solid #22c55e';

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

                <div class="feed-item" onclick="showUserDetail(${u.id})">

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

            document.getElementById('reportCount').textContent = reports.length;

        }



        // ===== CRUD OPERATIONS =====

        function openModal(type, data) {

            const modal = document.getElementById('modal');

            const title = document.getElementById('modalTitle');

            const body = document.getElementById('modalBody');



            const types = {

                user: {

                    title: data ? 'Edit User' : 'Add New User',

                    fields: `

                        <label>Full Name</label><input id="mName" value="${data?.name || ''}">

                        <label>Email</label><input id="mEmail" value="${data?.email || ''}">

                        <label>Role</label>

                        <select id="mRole">

                            <option value="student" ${data?.role === 'student' ? 'selected' : ''}>Student</option>

                            <option value="lecturer" ${data?.role === 'lecturer' ? 'selected' : ''}>Lecturer</option>

                        </select>

                        <label>Status</label>

                        <select id="mStatus">

                            <option value="active" ${data?.status === 'active' ? 'selected' : ''}>Active</option>

                            <option value="inactive" ${data?.status === 'inactive' ? 'selected' : ''}>Inactive</option>

                            <option value="warned" ${data?.status === 'warned' ? 'selected' : ''}>Warned</option>

                            <option value="blocked" ${data?.status === 'blocked' ? 'selected' : ''}>Blocked</option>

                        </select>

                    `

                },

                group: {

                    title: data ? 'Edit Group' : 'Create New Group',

                    fields: `

                        <label>Group Name</label><input id="mName" value="${data?.name || ''}">

                        <label>Description</label><input id="mDesc" value="${data?.description || ''}">

                        <label>Type</label>

                        <select id="mType">

                            <option value="public" ${data?.type === 'public' ? 'selected' : ''}>Public</option>

                            <option value="private" ${data?.type === 'private' ? 'selected' : ''}>Private</option>

                        </select>

                        <label>Status</label>

                        <select id="mStatus">

                            <option value="active" ${data?.status === 'active' ? 'selected' : ''}>Active</option>

                            <option value="warned" ${data?.status === 'warned' ? 'selected' : ''}>Flagged</option>

                            <option value="blocked" ${data?.status === 'blocked' ? 'selected' : ''}>Blocked</option>

                        </select>

                    `

                },

                topic: {

                    title: data ? 'Edit Topic' : 'Add New Topic',

                    fields: `

                        <label>Topic Name</label><input id="mName" value="${data?.name || ''}">

                        <label>Category</label>

                        <select id="mCategory">

                            <option value="Academic" ${data?.category === 'Academic' ? 'selected' : ''}>Academic</option>

                            <option value="General" ${data?.category === 'General' ? 'selected' : ''}>General</option>

                            <option value="Announcement" ${data?.category === 'Announcement' ? 'selected' : ''}>Announcement</option>

                        </select>

                        <label>Author</label><input id="mAuthor" value="${data?.author || ''}">

                        <label>Status</label>

                        <select id="mStatus">

                            <option value="active" ${data?.status === 'active' ? 'selected' : ''}>Active</option>

                            <option value="flagged" ${data?.status === 'flagged' ? 'selected' : ''}>Flagged</option>

                            <option value="pending" ${data?.status === 'pending' ? 'selected' : ''}>Pending</option>

                        </select>

                    `

                },

                warning: {

                    title: data ? 'Modify Warning' : 'Issue Warning',

                    fields: `

                        <label>User</label><input id="mUser" value="${data?.user || ''}">

                        <label>Reason</label>

                        <select id="mReason">

                            <option value="Spam" ${data?.reason === 'Spam' ? 'selected' : ''}>Spam</option>

                            <option value="Harassment" ${data?.reason === 'Harassment' ? 'selected' : ''}>Harassment</option>

                            <option value="Inactivity" ${data?.reason === 'Inactivity' ? 'selected' : ''}>Inactivity</option>

                            <option value="Academic misconduct" ${data?.reason === 'Academic misconduct' ? 'selected' : ''}>Academic misconduct</option>

                            <option value="Policy violation" ${data?.reason === 'Policy violation' ? 'selected' : ''}>Policy violation</option>

                        </select>

                        <label>Warning #</label><input id="mNumber" type="number" value="${data?.number || 1}">

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

            const name = document.getElementById('mName')?.value;

            const email = document.getElementById('mEmail')?.value;

            const role = document.getElementById('mRole')?.value;

            const status = document.getElementById('mStatus')?.value;

            const desc = document.getElementById('mDesc')?.value;

            const typeVal = document.getElementById('mType')?.value;

            const category = document.getElementById('mCategory')?.value;

            const author = document.getElementById('mAuthor')?.value;

            const user = document.getElementById('mUser')?.value;

            const reason = document.getElementById('mReason')?.value;

            const number = parseInt(document.getElementById('mNumber')?.value) || 1;



            if (type === 'user') {

                if (id) {

                    const u = users.find(u => u.id == id);

                    if (u) { u.name = name; u.email = email; u.role = role; u.status = status; }

                } else {

                    users.push({ id: nextIds.user++, name, email, role, status, verified: false, lastSeen: new Date().toISOString().replace('T', ' ').slice(0, 16) });

                }

                renderUsers();

            } else if (type === 'group') {

                if (id) {

                    const g = groups.find(g => g.id == id);

                    if (g) { g.name = name; g.description = desc; g.type = typeVal; g.status = status; }

                } else {

                    groups.push({ id: nextIds.group++, name, description: desc, members: 0, type: typeVal, posts: 0, status });

                }

                renderGroups();

            } else if (type === 'topic') {

                if (id) {

                    const t = topics.find(t => t.id == id);

                    if (t) { t.name = name; t.category = category; t.author = author; t.status = status; }

                } else {

                    topics.push({ id: nextIds.topic++, name, category, replies: 0, views: 0, engagement: 0, status, author });

                }

                renderTopics();

            } else if (type === 'warning') {

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

            }

            closeModal();

            updateKPIs();

            updateBadges();

        }



        // User actions

        function deleteUser(id) {

            if (confirm(`Are you sure you want to delete this user? This action cannot be undone.`)) {

                users = users.filter(u => u.id !== id);

                renderUsers();

                updateKPIs();

                updateBadges();

                // Renumber IDs

                users.forEach((u, idx) => u.id = idx + 1);

                nextIds.user = users.length + 1;

                renderUsers();

            }

        }



        function editUser(id) {

            const u = users.find(u => u.id === id);

            if (u) openModal('user', u);

        }



        function toggleUserStatus(id) {

            const u = users.find(u => u.id === id);

            if (u) {

                u.status = u.status === 'blocked' ? 'active' : 'blocked';

                renderUsers();

                updateKPIs();

            }

        }



        function verifyUser(id) {

            const u = users.find(u => u.id === id);

            if (u) { u.verified = true; renderUsers(); }

        }



        function showUserDetail(id) {

            const u = users.find(u => u.id === id);

            if (!u) return;

            const modal = document.getElementById('userDetailModal');

            const body = document.getElementById('userDetailBody');

            body.innerHTML = `

                <div class="detail-row"><span class="label">Full Name</span><span class="value">${u.name}</span></div>

                <div class="detail-row"><span class="label">Email</span><span class="value">${u.email}</span></div>

                <div class="detail-row"><span class="label">Role</span><span class="value"><span class="role-badge role-${u.role}">${u.role.charAt(0).toUpperCase() + u.role.slice(1)}</span></span></div>

                <div class="detail-row"><span class="label">Status</span><span class="value"><span class="status-badge ${u.status}">${u.status.charAt(0).toUpperCase() + u.status.slice(1)}</span></span></div>

                <div class="detail-row"><span class="label">Verified</span><span class="value">${u.verified ? '✅ Yes' : '❌ No'}</span></div>

                <div class="detail-row"><span class="label">Last Seen</span><span class="value">${u.lastSeen || 'N/A'}</span></div>

                <div class="detail-row"><span class="label">User ID</span><span class="value">#${String(u.id).padStart(4, '0')}</span></div>

                <div class="detail-row"><span class="label">Account Created</span><span class="value">2026-0${Math.floor(Math.random()*6)+1}-${String(Math.floor(Math.random()*28)+1).padStart(2,'0')}</span></div>

            `;

            modal.classList.add('show');

        }



        function notifyUser(id) {

            const u = users.find(u => u.id === id);

            if (u) alert(`📧 Notification sent to ${u.name} (${u.email})`);

        }



        // Group actions

        function deleteGroup(id) {

            if (confirm('Delete this group?')) {

                groups = groups.filter(g => g.id !== id);

                renderGroups();

                updateBadges();

            }

        }



        function editGroup(id) {

            const g = groups.find(g => g.id === id);

            if (g) openModal('group', g);

        }



        function toggleGroupStatus(id) {

            const g = groups.find(g => g.id === id);

            if (g) {

                g.status = g.status === 'blocked' ? 'active' : 'blocked';

                renderGroups();

            }

        }



        // Topic actions

        function deleteTopic(id) {

            if (confirm('Delete this topic?')) {

                topics = topics.filter(t => t.id !== id);

                renderTopics();

                updateBadges();

            }

        }



        function editTopic(id) {

            const t = topics.find(t => t.id === id);

            if (t) openModal('topic', t);

        }



        function flagTopic(id) {

            const t = topics.find(t => t.id === id);

            if (t) { t.status = 'flagged'; renderTopics(); }

        }



        function approveTopic(id) {

            const t = topics.find(t => t.id === id);

            if (t) { t.status = 'active'; renderTopics(); }

        }



        function togglePinTopic(id) {

            alert(`📌 Topic ${id} has been ${Math.random() > 0.5 ? 'pinned' : 'unpinned'}`);

        }



        // Warning actions

        function resolveWarning(id) {

            const w = warnings.find(w => w.id === id);

            if (w) { w.status = 'resolved'; renderWarnings(); }

        }



        function extendWarning(id) {

            const w = warnings.find(w => w.id === id);

            if (w) {

                const exp = new Date(w.expires);

                exp.setDate(exp.getDate() + 14);

                w.expires = exp.toISOString().split('T')[0];

                renderWarnings();

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

            }

        }



        // Appeal actions

        function approveAppeal(id) {

            appeals = appeals.filter(a => a.id !== id);

            renderWarnings();

        }



        function dismissAppeal(id) {

            appeals = appeals.filter(a => a.id !== id);

            renderWarnings();

        }



        // Report actions

        function resolveReport(id) {

            const r = reports.find(r => r.id === id);

            if (r) { r.status = 'resolved'; renderReports(); }

        }



        function dismissReport(id) {

            const r = reports.find(r => r.id === id);

            if (r) { r.status = 'dismissed'; renderReports(); }

        }



        // Settings

        function applyThemeColor() {

            const color = document.getElementById('themeColor').value;

            document.getElementById('colorPreview').style.background = color;

            document.querySelectorAll('.btn-primary').forEach(b => {

                b.style.background = color;

                b.style.borderColor = color;

            });

            document.querySelectorAll('.chart-box:hover').forEach(b => {

                b.style.borderColor = color;

            });

            document.querySelectorAll('.nav-item.active i').forEach(i => {

                i.style.color = color;

            });

            document.querySelectorAll('.action-btn.primary').forEach(b => {

                b.style.borderColor = color;

                b.style.color = color;

            });

        }



        function saveSettings() {

            alert('⚙️ Settings saved successfully!');

        }



        // Chart expansion

        function expandChart(type) {

            const modal = document.getElementById('chartModal');

            document.getElementById('chartModalTitle').textContent = 

                type === 'activity' ? 'User Activity (Last 7 Days)' :

                type === 'topics' ? 'Top Discussion Analytics' : 'User Status Distribution';

            

            const canvas = document.getElementById('expandedChart');

            const ctx = canvas.getContext('2d');

            

            // Destroy existing chart if any

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

                config = {

                    type: 'doughnut',

                    data: {

                        labels: ['Active', 'Inactive', 'Warned', 'Blocked'],

                        datasets: [{

                            data: [6, 2, 2, 2],

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

                reports: 'Reports & Moderation',

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

                new Chart(ctx3, {

                    type: 'doughnut',

                    data: {

                        labels: ['Active', 'Inactive', 'Warned', 'Blocked'],

                        datasets: [{

                            data: [6, 2, 2, 2],

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

        renderReports();

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



        console.log('✅ Institutional Forum Management System initialized with 10 users');

        console.log('📊 All features: CRUD operations, search, filters, charts, tooltips, and more!');

    </script>

</body>

</html>