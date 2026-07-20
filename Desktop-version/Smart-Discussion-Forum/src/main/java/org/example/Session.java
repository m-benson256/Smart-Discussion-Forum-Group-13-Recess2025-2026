package org.example;

public class Session {
    private static Long userId;
    private static String token;
    private static String userName;
    private static String userEmail;
    private static String userRole;

    public static void set( Long id, String token, String name, String email, String role) {
        userId = id;
        Session.token = token;
        Session.userName = name;
        Session.userEmail = email;
        Session.userRole = role;
    }

    public static Long getUserId() {   // NEW
        return userId;
    }

    public static String getToken() {
        return token;
    }

    public static String getUserName() {
        return userName;
    }

    public static String getUserEmail() {
        return userEmail;
    }

    public static String getUserRole() {
        return userRole;
    }

    public static java.net.http.HttpRequest.Builder authorizedRequestBuilder(String url) {
        return java.net.http.HttpRequest.newBuilder()
            .uri(java.net.URI.create(url))
            .header("Authorization", "Bearer " + token)
            .header("Accept", "application/json")
            .header("Content-Type", "application/json");
    }
    public static void clear() {
        userId = null;
        token = null;
        userName = null;
        userEmail = null;
        userRole = null;
    }
}
