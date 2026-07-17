package org.example;

public class Session {
    private static String token;
    private static String userName;
    private static String userEmail;
    private static String userRole;

    public static void set(String token, String name, String email, String role) {
        Session.token = token;
        Session.userName = name;
        Session.userEmail = email;
        Session.userRole = role;
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

    public static void clear() {
        token = null;
        userName = null;
        userEmail = null;
        userRole = null;
    }
}
