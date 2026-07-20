package org.example;

public class AppState {
    private static Long selectedGroupId;
    private static Long selectedTopicId;
    private static String selectedTopicTitle;
    public static String returnView;

    public static void setSelectedTopicTitle(String title) { selectedTopicTitle = title; }
    public static String getSelectedTopicTitle() { return selectedTopicTitle; }

    public static void setSelectedGroupId(Long id) {
        selectedGroupId = id;

    }

    public static Long getSelectedGroupId() {
        return selectedGroupId;
    }

    public static void setSelectedTopicId(Long id) { // NEW
        selectedTopicId = id;
    }

    public static Long getSelectedTopicId() { // NEW
        return selectedTopicId;
    }

    public static void setReturnView(String fxmlFile) {
        returnView = fxmlFile;
    }

    public static String getReturnView() {
        return returnView;
    }
}
