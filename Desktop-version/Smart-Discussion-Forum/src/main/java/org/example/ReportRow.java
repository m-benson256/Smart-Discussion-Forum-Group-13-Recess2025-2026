package org.example;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class ReportRow {
    @JsonProperty("student_name")
    private String student_name;
    @JsonProperty("quiz_title")
    private String quiz_title;
    private Integer score;
    @JsonProperty("total_marks")
    private Integer total_marks;
    @JsonProperty("submitted_at")
    private String submitted_at;

    public String getStudentName() { return student_name; }
    public String getQuizTitle() { return quiz_title; }
    public Integer getScore() { return score; }
    public Integer getTotalMarks() { return total_marks; }
    public String getSubmittedAt() { return submitted_at; }
}