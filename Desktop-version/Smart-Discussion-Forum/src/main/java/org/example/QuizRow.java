package org.example;

public class QuizRow {
    private final String title;
    private final String category;
    private final String detail; // due date OR submitted date, depending on the table
    private final String score;  // null for the missed table

    public QuizRow(String title, String category, String detail, String score) {
        this.title = title;
        this.category = category;
        this.detail = detail;
        this.score = score;
    }

    public String getTitle() { return title; }
    public String getCategory() { return category; }
    public String getDetail() { return detail; }
    public String getScore() { return score; }
}
