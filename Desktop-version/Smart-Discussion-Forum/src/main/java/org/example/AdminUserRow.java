package org.example;

public class AdminUserRow {
    private final long userId;
    private final String name;
    private final String email;
    private final String role;
    private final Long groupMemberId;   // null if user has no group memberships
    private final Long groupId;
    private final String status;        // "active" / "inactive" / "blacklisted" / "n/a"
    private final String blacklistedUntil; // raw string from API, null if not applicable

    public AdminUserRow(long userId, String name, String email, String role,
                        Long groupMemberId, Long groupId, String status, String blacklistedUntil) {
        this.userId = userId;
        this.name = name;
        this.email = email;
        this.role = role;
        this.groupMemberId = groupMemberId;
        this.groupId = groupId;
        this.status = status;
        this.blacklistedUntil = blacklistedUntil;
    }

    public long getUserId() { return userId; }
    public String getName() { return name; }
    public String getEmail() { return email; }
    public String getRole() { return role; }
    public Long getGroupMemberId() { return groupMemberId; }
    public Long getGroupId() { return groupId; }
    public String getStatus() { return status; }
    public String getBlacklistedUntil() { return blacklistedUntil == null ? "-" : blacklistedUntil; }
}
