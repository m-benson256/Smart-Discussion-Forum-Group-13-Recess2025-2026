package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Announcement {
    private Long id;
    private String content;
    private String created_at;
    private UserRef user;
    private QuizRef quiz;

    public Long getId() { return id; }
    public String getContent() { return content; }
    public String getCreatedAt() { return created_at; }
    public UserRef getUser() { return user; }
    public QuizRef getQuiz() { return quiz; }

    @JsonIgnoreProperties(ignoreUnknown = true)
    public static class UserRef {
        private Long id;
        private String name;
        public Long getId() { return id; }
        public String getName() { return name; }
    }

    @JsonIgnoreProperties(ignoreUnknown = true)
    public static class QuizRef {
        private Long id;
        private String title;
        public Long getId() { return id; }
        public String getTitle() { return title; }
    }
}