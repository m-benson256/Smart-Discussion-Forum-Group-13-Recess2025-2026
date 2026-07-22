package org.example;

import com.fasterxml.jackson.annotation.JsonAlias;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Quiz {
    private Long id;
    private String title;
    private String description;
    private Long category_id;
    private String start_time;
    private Integer duration_minutes;
    private Integer total_marks;
    private Integer passing_score;
    private boolean shuffle_questions;
    private String status;
    private Long created_by;

    @JsonAlias({"questionsCount", "questions_count"})
    private Integer questions_count;

    @JsonAlias({"submissionsCount", "submissions_count"})
    private Integer submissions_count;

    
    public Long getId() { return id; }
    public String getTitle() { return title; }
    public String getDescription() { return description; }
    public Long getCategoryId() { return category_id; }
    public String getStartTime() { return start_time; }
    public Integer getDurationMinutes() { return duration_minutes; }
    public Integer getTotalMarks() { return total_marks; }
    public Integer getPassingScore() { return passing_score; }
    public boolean isShuffleQuestions() { return shuffle_questions; }
    public String getStatus() { return status; }
    public Long getCreatedBy() { return created_by; }
    public Integer getQuestionsCount() { return questions_count; }
    public Integer getSubmissionsCount() { return submissions_count; }
}